<?php

namespace App\Http\Controllers;

use App\Models\Postinertia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostinertiaController extends Controller
{
    public function index()
    {
        return Inertia::render('PostsInertia/Index', [
            'posts' => Postinertia::select('id', 'title', 'author', 'content', 'created_at')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('PostsInertia/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'author'  => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Postinertia::create($data);

        return redirect()->route('postsinertia.index')
            ->with('message', 'Post created successfully');
    }

    public function edit(Postinertia $postinertia)
    {
        return Inertia::render('PostsInertia/Edit', [
            'post' => $postinertia->only('id', 'title', 'author', 'content'),
        ]);
    }

    public function update(Request $request, Postinertia $postinertia)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'author'  => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $postinertia->update($data);

        return redirect()->route('postsinertia.index')
            ->with('message', 'Post updated successfully');
    }

    public function destroy(Postinertia $postinertia)
    {
        $postinertia->delete();

        return redirect()->route('postsinertia.index')
            ->with('message', 'Post deleted successfully');
    }
}
