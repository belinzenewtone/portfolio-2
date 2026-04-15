<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogList;
use App\Models\BlogPost;
use App\Models\Education;
use App\Models\Project;
use App\Models\ReadingItem;
use App\Models\TimelineEvent;
use App\Models\WorkExperience;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'projects'        => Project::count(),
                'work_experiences'=> WorkExperience::count(),
                'educations'      => Education::count(),
                'blog_posts'      => BlogPost::count(),
                'lists'           => BlogList::count(),
                'reading'         => ReadingItem::count(),
                'timeline'        => TimelineEvent::count(),
            ],
        ]);
    }
}
