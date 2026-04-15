<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\WorkExperienceController;
use App\Http\Controllers\Admin\EducationController;
use Illuminate\Support\Facades\Route;

// Public portfolio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo');

        // Projects
        Route::resource('projects', ProjectController::class)->except(['show']);

        // Work Experiences
        Route::resource('work-experiences', WorkExperienceController::class)->except(['show']);

        // Education
        Route::resource('educations', EducationController::class)->except(['show']);
    });
});
