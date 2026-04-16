<?php

namespace App\Http\Controllers;

use App\Models\BlogList;
use App\Models\BlogPost;
use App\Models\Education;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ReadingItem;
use App\Models\Skill;
use App\Models\TimelineEvent;
use App\Models\WorkExperience;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $profile         = Profile::first();
        $projects        = Project::ordered()->where('is_visible', true)->get();
        $workExperiences = WorkExperience::ordered()->get();
        $educations      = Education::ordered()->get();
        $blogPosts       = BlogPost::published()->get();
        $lists           = BlogList::ordered()->published()->with(['items' => fn($q) => $q->orderBy('sort_order')])->get();
        $readingItems    = ReadingItem::ordered()->get();
        $timelineEvents  = TimelineEvent::ordered()->get();
        $skills          = Skill::ordered()->visible()->get();

        return Inertia::render('Home', [
            'profile' => $profile,

            'projects' => $projects->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'description' => $p->description,
                'date'        => $p->date?->format('d M Y'),
                'logo_url'    => $p->logo_url,
                'project_url' => $p->project_url,
                'bg_color'    => $p->bg_color,
                'is_featured' => $p->is_featured,
            ]),

            'skills' => $skills->map(fn($s) => [
                'id'       => $s->id,
                'name'     => $s->name,
                'category' => $s->category,
                'icon_url' => $s->icon_url,
                'color'    => $s->color,
            ]),

            'workExperiences' => $workExperiences->map(fn($w) => [
                'id'              => $w->id,
                'company'         => $w->company,
                'role'            => $w->role,
                'description'     => $w->description,
                'period'          => $w->period,
                'location'        => $w->location,
                'employment_type' => $w->employment_type,
                'is_current'      => $w->is_current,
                'logo_url'        => $w->logo_url,
            ]),

            'educations' => $educations->map(fn($e) => [
                'id'          => $e->id,
                'institution' => $e->institution,
                'degree'      => $e->degree,
                'field'       => $e->field,
                'description' => $e->description,
                'period'      => $e->period,
                'logo_url'    => $e->logo_url,
            ]),

            'blogPosts' => $blogPosts->map(fn($b) => [
                'id'              => $b->id,
                'title'           => $b->title,
                'slug'            => $b->slug,
                'excerpt'         => $b->excerpt,
                'external_url'    => $b->external_url,
                'cover_image_url' => $b->cover_image_url,
                'published_at'    => $b->published_at?->format('d M Y'),
            ]),

            'lists' => $lists->map(fn($l) => [
                'id'          => $l->id,
                'title'       => $l->title,
                'description' => $l->description,
                'emoji'       => $l->emoji,
                'items'       => $l->items->map(fn($i) => [
                    'id'           => $i->id,
                    'text'         => $i->text,
                    'url'          => $i->url,
                    'note'         => $i->note,
                    'is_completed' => $i->is_completed,
                ]),
            ]),

            'readingItems' => $readingItems->map(fn($r) => [
                'id'        => $r->id,
                'title'     => $r->title,
                'author'    => $r->author,
                'cover_url' => $r->cover_url,
                'status'    => $r->status,
                'year_read' => $r->year_read,
                'rating'    => $r->rating,
                'notes'     => $r->notes,
                'buy_url'   => $r->buy_url,
            ]),

            'timelineEvents' => $timelineEvents->map(fn($t) => [
                'id'          => $t->id,
                'title'       => $t->title,
                'description' => $t->description,
                'event_date'  => $t->event_date?->format('d M Y'),
                'event_year'  => $t->event_date?->format('Y'),
                'category'    => $t->category,
                'emoji'       => $t->emoji,
            ]),
        ]);
    }
}
