<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReadingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reading/Index', [
            'items' => ReadingItem::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Reading/Form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'cover_url'   => 'nullable|url|max:500',
            'cover_image' => 'nullable|image|max:2048',
            'status'      => 'required|in:want_to_read,currently_reading,completed',
            'year_read'   => 'nullable|integer|min:1900|max:2100',
            'rating'      => 'nullable|integer|min:1|max:5',
            'notes'       => 'nullable|string',
            'buy_url'     => 'nullable|url|max:500',
            'sort_order'  => 'integer|min:0',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_url'] = '/storage/' . $request->file('cover_image')->store('covers', 'public');
        }
        unset($data['cover_image']);

        ReadingItem::create($data);

        return redirect()->route('admin.reading.index')->with('success', 'Item added.');
    }

    public function edit(ReadingItem $reading)
    {
        return Inertia::render('Admin/Reading/Form', ['item' => $reading]);
    }

    public function update(Request $request, ReadingItem $reading)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'cover_url'   => 'nullable|url|max:500',
            'cover_image' => 'nullable|image|max:2048',
            'status'      => 'required|in:want_to_read,currently_reading,completed',
            'year_read'   => 'nullable|integer|min:1900|max:2100',
            'rating'      => 'nullable|integer|min:1|max:5',
            'notes'       => 'nullable|string',
            'buy_url'     => 'nullable|url|max:500',
            'sort_order'  => 'integer|min:0',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old uploaded cover if it was stored locally
            if ($reading->cover_url && str_starts_with($reading->cover_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $reading->cover_url));
            }
            $data['cover_url'] = '/storage/' . $request->file('cover_image')->store('covers', 'public');
        }
        unset($data['cover_image']);

        $reading->update($data);

        return redirect()->route('admin.reading.index')->with('success', 'Item updated.');
    }

    public function destroy(ReadingItem $reading)
    {
        $reading->delete();
        return redirect()->route('admin.reading.index')->with('success', 'Item deleted.');
    }
}
