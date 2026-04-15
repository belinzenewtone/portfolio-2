<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Blog/Index', [
            'posts' => BlogPost::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Blog/Form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt'        => 'nullable|string',
            'content'        => 'nullable|string',
            'cover_image_url'=> 'nullable|url|max:500',
            'external_url'   => 'nullable|url|max:500',
            'published_at'   => 'nullable|date',
            'is_published'   => 'boolean',
            'sort_order'     => 'integer|min:0',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post created.');
    }

    public function edit(BlogPost $blog)
    {
        return Inertia::render('Admin/Blog/Form', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt'        => 'nullable|string',
            'content'        => 'nullable|string',
            'cover_image_url'=> 'nullable|url|max:500',
            'external_url'   => 'nullable|url|max:500',
            'published_at'   => 'nullable|date',
            'is_published'   => 'boolean',
            'sort_order'     => 'integer|min:0',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Post deleted.');
    }
}
