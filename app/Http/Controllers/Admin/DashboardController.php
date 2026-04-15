<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Project;
use App\Models\WorkExperience;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'projects' => Project::count(),
                'work_experiences' => WorkExperience::count(),
                'educations' => Education::count(),
            ],
        ]);
    }
}
