<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimelineController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Timeline/Index', [
            'events' => TimelineEvent::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Timeline/Form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date'  => 'required|date',
            'category'    => 'required|in:career,education,life,other',
            'emoji'       => 'nullable|string|max:10',
            'sort_order'  => 'integer|min:0',
        ]);

        TimelineEvent::create($data);

        return redirect()->route('admin.timeline.index')->with('success', 'Event added.');
    }

    public function edit(TimelineEvent $timeline)
    {
        return Inertia::render('Admin/Timeline/Form', ['event' => $timeline]);
    }

    public function update(Request $request, TimelineEvent $timeline)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date'  => 'required|date',
            'category'    => 'required|in:career,education,life,other',
            'emoji'       => 'nullable|string|max:10',
            'sort_order'  => 'integer|min:0',
        ]);

        $timeline->update($data);

        return redirect()->route('admin.timeline.index')->with('success', 'Event updated.');
    }

    public function destroy(TimelineEvent $timeline)
    {
        $timeline->delete();
        return redirect()->route('admin.timeline.index')->with('success', 'Event deleted.');
    }
}
