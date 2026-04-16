<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        return Inertia::render('Admin/Profile/Edit', [
            'profile' => Profile::first(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'title'             => 'required|string|max:255',
            'subtitle'          => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'bio'               => 'nullable|string',
            'email'             => 'nullable|email|max:255',
            'github_url'        => 'nullable|url|max:255',
            'linkedin_url'      => 'nullable|url|max:255',
            'twitter_url'       => 'nullable|url|max:255',
            'current_company'   => 'nullable|string|max:255',
            'years_experience'  => 'nullable|string|max:50',
            'cv_url'            => 'nullable|url|max:255',
            'is_available'      => 'boolean',
            'availability_text' => 'nullable|string|max:100',
            'show_availability' => 'boolean',
            'footer_email'      => 'nullable|email|max:255',
            'footer_name'       => 'nullable|string|max:255',
        ]);

        $profile = Profile::first();
        if ($profile) {
            $profile->update($validated);
        } else {
            Profile::create($validated);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate(['photo' => 'required|image|max:2048']);

        $profile = Profile::first();
        if (!$profile) {
            return back()->withErrors(['photo' => 'Profile not found.']);
        }

        if ($profile->profile_photo && !str_starts_with($profile->profile_photo, 'http')) {
            Storage::disk('public')->delete($profile->profile_photo);
        }

        $path = $request->file('photo')->store('photos', 'public');
        $profile->update(['profile_photo' => $path]);

        return back()->with('success', 'Photo uploaded successfully.');
    }
}
