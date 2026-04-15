<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Projects/Index', [
            'projects' => Project::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Projects/Form', ['project' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'project_url' => 'nullable|url|max:255',
            'bg_color' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
            'is_visible' => 'boolean',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_url'] = $request->file('logo')->store('logos', 'public');
        }
        unset($validated['logo']);

        Project::create($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Form', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'project_url' => 'nullable|url|max:255',
            'bg_color' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
            'is_visible' => 'boolean',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($project->logo_url && !str_starts_with($project->logo_url, 'http')) {
                Storage::disk('public')->delete($project->logo_url);
            }
            $validated['logo_url'] = $request->file('logo')->store('logos', 'public');
        }
        unset($validated['logo']);

        $project->update($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        if ($project->logo_url && !str_starts_with($project->logo_url, 'http')) {
            Storage::disk('public')->delete($project->logo_url);
        }
        $project->delete();
        return back()->with('success', 'Project deleted.');
    }
}
