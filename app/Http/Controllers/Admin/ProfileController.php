<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
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
            'cv_url'            => 'nullable|url|max:500',
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

    // Photo URL is set by the frontend after a direct Cloudinary upload
    public function uploadPhoto(Request $request)
    {
        $request->validate(['photo_url' => 'required|url|max:500']);

        $profile = Profile::first();
        if (!$profile) {
            return back()->withErrors(['photo_url' => 'Profile not found.']);
        }

        $profile->update(['profile_photo' => $request->photo_url]);

        return back()->with('success', 'Photo updated successfully.');
    }

    // CV URL is set by the frontend after a direct Cloudinary upload
    public function updateCv(Request $request)
    {
        $request->validate(['cv_url' => 'required|url|max:500']);

        $profile = Profile::firstOrCreate([]);
        $profile->update(['cv_url' => $request->cv_url]);

        return back()->with('success', 'CV uploaded successfully.');
    }

    public function removeCv()
    {
        $profile = Profile::first();
        if ($profile) {
            $profile->update(['cv_url' => null]);
        }

        return back()->with('success', 'CV removed.');
    }
}
