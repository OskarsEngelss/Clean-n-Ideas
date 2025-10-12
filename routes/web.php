<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\TutorialListController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ExperienceController::class, 'index'])->name('home');
Route::get('/load-more', [ExperienceController::class, 'loadMore'])->name('experiences.loadMore');

Route::get('/experiences/{experience:slug}', [ExperienceController::class, 'show'])->name('experience.show');

Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/search', [SearchController::class, 'search'])->name('search');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');


    Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('follow.toggle');


    Route::get('/lists', function () {
        return view('lists');
    })->name('lists');


    Route::get('/your-experiences', [ExperienceController::class, 'yourExperiences'])->name('your-experiences');
    Route::get('/experience/create', [ExperienceController::class, 'create'])->name('experience.create');
    Route::post('/upload-temp', [ExperienceController::class, 'uploadTemp']);
    Route::post('/delete-temp', [ExperienceController::class, 'deleteTemp']);
    Route::post('/experience/store', [ExperienceController::class, 'store'])->name('experience.store');
    Route::delete('/experience/{id}/delete', [ExperienceController::class, 'destroy'])->name('experience.destroy');

    Route::post('/tutorial/react', [ExperienceController::class, 'toggleReaction'])->name('experience.react');


    Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/comment/react', [CommentController::class, 'toggleReaction'])->name('comment.react');


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
