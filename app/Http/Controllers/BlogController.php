<?php
namespace App\Http\Controllers;
use App\Models\BlogPost;
use App\Models\Profile;
use Inertia\Inertia;
class BlogController extends Controller
{
    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return Inertia::render('Blog/Show', [
            'post'    => $post,
            'profile' => Profile::first(),
        ]);
    }
}
