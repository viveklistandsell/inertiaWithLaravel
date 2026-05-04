<?php

use App\Models\User;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\PostinertiaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

$localePath = fn (string $path = '') => '/' . app()->getLocale() . ($path === '' ? '' : '/' . ltrim($path, '/'));

Route::get('/', fn () => redirect('/' . config('app.locale')));

Route::prefix('{locale}')
    ->where(['locale' => 'de|en'])
    ->middleware(SetLocale::class)
    ->group(function () use ($localePath) {

        Route::get('/', function () {
            return Inertia::render('Welcome', [
                'name' => 'John Doe',
                'framworks' => ['Laravel', 'Vue', 'Inertia'],
            ]);
        });

        Route::middleware('guest')->group(function () use ($localePath) {
            Route::get('/login', fn () => Inertia::render('Login'))->name('login');

            Route::post('/login', function () use ($localePath) {
                $credentials = request()->validate([
                    'email'    => 'required|email',
                    'password' => 'required|string',
                ]);

                if (! Auth::attempt($credentials, (bool) request('remember'))) {
                    return back()->withErrors(['email' => 'These credentials do not match our records.']);
                }

                request()->session()->regenerate();
                return redirect($localePath('user'))->with('message', 'Welcome back, ' . Auth::user()->name . '!');
            });
        });

        Route::post('/logout', function () use ($localePath) {
            $name = Auth::user()?->name;
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect($localePath('login'))->with('message', $name ? "Goodbye, {$name}!" : 'Logged out');
        })->middleware('auth');

        Route::middleware('auth')->group(function () use ($localePath) {
            Route::get('/user', function () {
                $search    = request('search', '');
                $sort      = in_array(request('sort'), ['id', 'name', 'email']) ? request('sort') : 'id';
                $direction = request('direction') === 'desc' ? 'desc' : 'asc';
                $perPage   = in_array((int) request('per_page'), [10, 25, 50, 100]) ? (int) request('per_page') : 10;

                $query = User::select('id', 'name', 'email', 'role');

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                $query->orderBy($sort, $direction);

                return Inertia::render('Users', [
                    'users'   => $query->paginate($perPage)->withQueryString(),
                    'filters' => [
                        'search'    => $search,
                        'sort'      => $sort,
                        'direction' => $direction,
                        'per_page'  => $perPage,
                    ],
                ]);
            });

            Route::get('/user/check-email', function () {
                $email = (string) request('email', '');
                $available = $email !== ''
                    && filter_var($email, FILTER_VALIDATE_EMAIL)
                    && ! User::where('email', $email)->exists();
                return response()->json(['email' => $email, 'available' => $available]);
            });

            Route::middleware('can:manage_users')->group(function () use ($localePath) {
                Route::get('/user/create', fn () => Inertia::render('UserCreate'));

                Route::post('/user', function () use ($localePath) {
                    $data = request()->validate([
                        'name'     => 'required|string|min:2|max:255',
                        'email'    => 'required|email|max:255|unique:users,email',
                        'phone'    => 'nullable|string|max:255',
                        'address'  => 'nullable|string|max:500',
                        'password' => 'required|string|min:6|max:255|confirmed',
                    ]);
                    $data['password']          = Hash::make($data['password']);
                    $data['email_verified_at'] = now();
                    $data['remember_token']    = Str::random(10);
                    $user = User::forceCreate($data);
                    return redirect($localePath('user'))->with('message', "User '{$user->name}' created successfully");
                });

                Route::get('/user/{user}/edit', function (string $locale, User $user) {
                    return Inertia::render('UserEdit', [
                        'user'    => $user->only('id', 'name', 'email', 'phone', 'address'),
                        'filters' => request()->only(['search', 'sort', 'direction', 'per_page', 'page']),
                    ]);
                });

                Route::put('/user/{user}', function (string $locale, User $user) use ($localePath) {
                    $data = request()->validate([
                        'name'    => 'required|string|max:255',
                        'email'   => 'required|email|max:255|unique:users,email,' . $user->id,
                        'phone'   => 'nullable|string|max:255',
                        'address' => 'nullable|string|max:500',
                    ]);
                    $user->update($data);
                    $filters = array_filter(request()->only(['search', 'sort', 'direction', 'per_page', 'page']), fn ($v) => $v !== null && $v !== '');
                    $qs = $filters ? '?' . http_build_query($filters) : '';
                    return redirect($localePath('user') . $qs)->with('message', "User '{$user->name}' updated successfully");
                });
            });

            Route::get('/setting', fn () => Inertia::render('Settings'));
        });

        Route::resource('postsinertia', PostinertiaController::class)
            ->except(['show'])
            ->parameters(['postsinertia' => 'postinertia']);
    });

Route::get('/posts', fn () => view('posts'));
