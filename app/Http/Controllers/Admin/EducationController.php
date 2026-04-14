<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EducationController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Educations/Index', [
            'educations' => Education::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Educations/Form', ['education' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'period_start' => 'required|date',
            'period_end' => 'nullable|date',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_url'] = $request->file('logo')->store('logos', 'public');
        }
        unset($validated['logo']);

        Education::create($validated);
        return redirect()->route('admin.educations.index')->with('success', 'Education created.');
    }

    public function edit(Education $education)
    {
        return Inertia::render('Admin/Educations/Form', ['education' => $education]);
    }

    public function update(Request $request, Education $education)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'period_start' => 'required|date',
            'period_end' => 'nullable|date',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($education->logo_url && !str_starts_with($education->logo_url, 'http')) {
                Storage::disk('public')->delete($education->logo_url);
            }
            $validated['logo_url'] = $request->file('logo')->store('logos', 'public');
        }
        unset($validated['logo']);

        $education->update($validated);
        return redirect()->route('admin.educations.index')->with('success', 'Education updated.');
    }

    public function destroy(Education $education)
    {
        if ($education->logo_url && !str_starts_with($education->logo_url, 'http')) {
            Storage::disk('public')->delete($education->logo_url);
        }
        $education->delete();
        return back()->with('success', 'Education deleted.');
    }
}
