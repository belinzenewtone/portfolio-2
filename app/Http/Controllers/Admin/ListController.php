<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogList;
use App\Models\ListItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Lists/Index', [
            'lists' => BlogList::ordered()->withCount('items')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Lists/Form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'emoji'       => 'nullable|string|max:10',
            'is_published'=> 'boolean',
            'sort_order'  => 'integer|min:0',
            'items'       => 'nullable|array',
            'items.*.text'        => 'required|string|max:500',
            'items.*.url'         => 'nullable|url|max:500',
            'items.*.note'        => 'nullable|string|max:255',
            'items.*.is_completed'=> 'boolean',
        ]);

        $list = BlogList::create([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'emoji'        => $data['emoji'] ?? '📋',
            'is_published' => $data['is_published'] ?? true,
            'sort_order'   => $data['sort_order'] ?? 0,
        ]);

        foreach ($data['items'] ?? [] as $i => $item) {
            $list->items()->create([
                'text'         => $item['text'],
                'url'          => $item['url'] ?? null,
                'note'         => $item['note'] ?? null,
                'sort_order'   => $i,
                'is_completed' => $item['is_completed'] ?? false,
            ]);
        }

        return redirect()->route('admin.lists.index')->with('success', 'List created.');
    }

    public function edit(BlogList $list)
    {
        return Inertia::render('Admin/Lists/Form', [
            'list' => $list->load('items'),
        ]);
    }

    public function update(Request $request, BlogList $list)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'emoji'       => 'nullable|string|max:10',
            'is_published'=> 'boolean',
            'sort_order'  => 'integer|min:0',
            'items'       => 'nullable|array',
            'items.*.id'          => 'nullable|integer',
            'items.*.text'        => 'required|string|max:500',
            'items.*.url'         => 'nullable|url|max:500',
            'items.*.note'        => 'nullable|string|max:255',
            'items.*.is_completed'=> 'boolean',
        ]);

        $list->update([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'emoji'        => $data['emoji'] ?? '📋',
            'is_published' => $data['is_published'] ?? true,
            'sort_order'   => $data['sort_order'] ?? 0,
        ]);

        // Replace all items
        $list->items()->delete();
        foreach ($data['items'] ?? [] as $i => $item) {
            $list->items()->create([
                'text'         => $item['text'],
                'url'          => $item['url'] ?? null,
                'note'         => $item['note'] ?? null,
                'sort_order'   => $i,
                'is_completed' => $item['is_completed'] ?? false,
            ]);
        }

        return redirect()->route('admin.lists.index')->with('success', 'List updated.');
    }

    public function destroy(BlogList $list)
    {
        $list->delete(); // items cascade
        return redirect()->route('admin.lists.index')->with('success', 'List deleted.');
    }
}
