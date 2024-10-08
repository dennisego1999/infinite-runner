<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Enforce safety and performance checks
        DB::prohibitDestructiveCommands(app()->isProduction());
        Model::preventLazyLoading(! app()->isProduction());
        //Model::preventSilentlyDiscardingAttributes(! app()->isProduction());

        // Disable wrapping API resources
        JsonResource::withoutWrapping();

        /**
         * @see https://laravel.com/docs/eloquent-relationships#custom-polymorphic-types
         */
        Relation::enforceMorphMap([
            'team' => Team::class,
            'user' => User::class,
        ]);
    }
}
