<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TutorialList;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            $favouritesListId = $user 
                ? TutorialList::where('user_id', $user->id)
                    ->where('is_favourite', true)
                    ->value('id')
                : null;

            $view->with('favouritesListId', $favouritesListId);
        });
    }
}
