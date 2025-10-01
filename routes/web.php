<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\TutorialListController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ExperienceController::class, 'index'])->name('home');
Route::get('/experiences/{experience:slug}', [ExperienceController::class, 'show'])->name('experience.show');

Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('follow.toggle');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');

    Route::get('/favourites', function () {
        return view('favourites');
    })->name('favourites');
    Route::get('/lists', function () {
        return view('lists');
    })->name('lists');

    Route::get('/your-experiences', [ExperienceController::class, 'yourExperiences'])->name('your-experiences');
    Route::get('/experience/create', [ExperienceController::class, 'create'])->name('experience.create');
    Route::post('/experience/store', [ExperienceController::class, 'store'])->name('experience.store');

    Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store');

    Route::post('/tutorialList/favourite/store', [TutorialListController::class, 'favouriteStore'])->name('tutorialList.favourite.store');

    Route::get('/favourites', [TutorialListController::class, 'index'])->name('favourites');
    
    Route::get('/followers', [ProfileController::class, 'followers'])->name('followers');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
