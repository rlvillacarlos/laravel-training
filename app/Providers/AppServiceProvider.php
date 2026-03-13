<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('play-game', function (User $user, Game $game, bool $isCreator) {
            return ($isCreator ? true : $user->games()->find($game->id)) ?
                Response::allow()
                : Response::denyWithStatus(404);
        });
    }
}
