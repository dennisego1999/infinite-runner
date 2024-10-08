<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: (function ($router) {
            Route::prefix(LaravelLocalization::setLocale())
                ->middleware(['web', 'localization'])
                ->group(base_path('routes/web.php'));
        })
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('localization', [
            \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        ]);

        $middleware->append([
            \App\Http\Middleware\NoIndexMiddleware::class.':production',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\EnsureLocaleIsPassed::class,
            \Spatie\Csp\AddCspHeaders::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
