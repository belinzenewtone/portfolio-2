<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class WorkExperienceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/WorkExperiences/Index', [
            'workExperiences' => WorkExperience::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/WorkExperiences/Form', ['workExperience' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period_start' => 'required|date',
            'period_end' => 'nullable|date',
            'is_current' => 'boolean',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:100',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_url'] = $request->file('logo')->store('logos', 'public');
        }
        unset($validated['logo']);

        WorkExperience::create($validated);
        return redirect()->route('admin.work-experiences.index')->with('success', 'Work experience created.');
    }

    public function edit(WorkExperience $workExperience)
    {
        return Inertia::render('Admin/WorkExperiences/Form', ['workExperience' => $workExperience]);
    }

    public function update(Request $request, WorkExperience $workExperience)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period_start' => 'required|date',
            'period_end' => 'nullable|date',
            'is_current' => 'boolean',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:100',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($workExperience->logo_url && !str_starts_with($workExperience->logo_url, 'http')) {
                Storage::disk('public')->delete($workExperience->logo_url);
            }
            $validated['logo_url'] = $request->file('logo')->store('logos', 'public');
        }
        unset($validated['logo']);

        $workExperience->update($validated);
        return redirect()->route('admin.work-experiences.index')->with('success', 'Work experience updated.');
    }

    public function destroy(WorkExperience $workExperience)
    {
        if ($workExperience->logo_url && !str_starts_with($workExperience->logo_url, 'http')) {
            Storage::disk('public')->delete($workExperience->logo_url);
        }
        $workExperience->delete();
        return back()->with('success', 'Work experience deleted.');
    }
}
