<?php

namespace App\Http\Controllers;

use App\Models\Postinertia;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Postinertia::select('id', 'title', 'author', 'content')
            ->latest()
            ->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'author'  => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Postinertia::create($validated);

        return response()->json($post, 201);
    }

    public function show(Postinertia $post)
    {
        return $post;
    }

    public function update(Request $request, Postinertia $post)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'author'  => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        return response()->json($post, 200);
    }

    public function destroy(Postinertia $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
