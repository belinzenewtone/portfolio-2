<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Profile;
use App\Models\Project;
use App\Models\WorkExperience;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        $projects = Project::ordered()->where('is_visible', true)->get();
        $workExperiences = WorkExperience::ordered()->get();
        $educations = Education::ordered()->get();

        return Inertia::render('Home', [
            'profile' => $profile,
            'projects' => $projects->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'date' => $p->date?->format('d M Y'),
                'logo_url' => $p->logo_url,
                'project_url' => $p->project_url,
                'bg_color' => $p->bg_color,
            ]),
            'workExperiences' => $workExperiences->map(fn($w) => [
                'id' => $w->id,
                'company' => $w->company,
                'role' => $w->role,
                'description' => $w->description,
                'period' => $w->period,
                'location' => $w->location,
                'employment_type' => $w->employment_type,
                'logo_url' => $w->logo_url,
            ]),
            'educations' => $educations->map(fn($e) => [
                'id' => $e->id,
                'institution' => $e->institution,
                'degree' => $e->degree,
                'field' => $e->field,
                'description' => $e->description,
                'period' => $e->period,
                'logo_url' => $e->logo_url,
            ]),
        ]);
    }
}
