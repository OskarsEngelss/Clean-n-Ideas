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
Route::get('/user/{user}/load-more', [ProfileController::class, 'loadMoreExperiences'])->name('profile.loadMoreExperiences');

Route::get('/search', [SearchController::class, 'search'])->name('search');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');


    Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('follow.toggle');


    Route::get('/your-experiences', [ExperienceController::class, 'yourExperiences'])->name('your-experiences');
    Route::get('/your-experiences/load-more', [ExperienceController::class, 'yourExperiencesLoadMore'])->name('your-experiences.loadMore');

    Route::get('/experience/create', [ExperienceController::class, 'create'])->name('experience.create');
    Route::post('/upload-temp', [ExperienceController::class, 'uploadTemp']);
    Route::post('/delete-temp', [ExperienceController::class, 'deleteTemp']);
    Route::post('/experience/store', [ExperienceController::class, 'store'])->name('experience.store');
    Route::delete('/experience/{id}/delete', [ExperienceController::class, 'destroy'])->name('experience.destroy');

    Route::post('/tutorial/react', [ExperienceController::class, 'toggleReaction'])->name('experience.react');


    Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/comment/react', [CommentController::class, 'toggleReaction'])->name('comment.react');


    Route::get('/lists/{id}', [TutorialListController::class, 'index'])->name('list.index');
    Route::get('/lists/{id}/load-more', [TutorialListController::class, 'listsLoadMore'])->name('list.loadMore');
    Route::get('/experiences/{experience:slug}/lists/load-more', [TutorialListController::class, 'experiencesListsLoadMore'])->name('experience.listsLoadMore');
    Route::get('/lists/show/{id}/{list_id}', [TutorialListController::class, 'show'])->name('list.show');

    Route::post('/lists/storeList', [TutorialListController::class, 'storeList'])->name('list.storeList');
    Route::post('/lists/storeTutorial', [TutorialListController::class, 'storeTutorial'])->name('list.storeTutorial');

    
    Route::get('/followers', [ProfileController::class, 'followers'])->name('followers');
    Route::get('/followers/load-more', [ProfileController::class, 'followersLoadMore'])->name('followers.loadMore');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

require __DIR__.'/auth.php';
