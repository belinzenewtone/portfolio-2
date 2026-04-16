<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ListController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReadingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\TimelineController;
use App\Http\Controllers\Admin\WorkExperienceController;
use Illuminate\Support\Facades\Route;

// Public portfolio
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile',       [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile',       [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/photo',[ProfileController::class, 'uploadPhoto'])->name('profile.photo');

        // Content sections
        Route::resource('projects',        ProjectController::class)->except(['show']);
        Route::resource('work-experiences',WorkExperienceController::class)->except(['show']);
        Route::resource('educations',      EducationController::class)->except(['show']);
        Route::resource('blog',            AdminBlogController::class)->except(['show']);
        Route::resource('lists',           ListController::class)->except(['show']);
        Route::resource('reading',         ReadingController::class)->except(['show']);
        Route::resource('timeline',        TimelineController::class)->except(['show']);
        Route::resource('skills',          SkillController::class)->except(['show']);
        Route::post('/skills/reorder',     [SkillController::class, 'reorder'])->name('skills.reorder');
        Route::post('/projects/reorder',   [ProjectController::class, 'reorder'])->name('projects.reorder');
        Route::get('/messages',            [MessageController::class, 'index'])->name('messages.index');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });
});
