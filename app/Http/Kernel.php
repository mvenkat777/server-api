<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        // \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\Cors::class,
        \App\Http\Middleware\LogAfterRequest::class,
        \Platform\App\Http\Middlewares\EtagsMiddleware::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'check.god' => \App\Http\Middleware\CheckGod::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // 'httpLog' =>  \App\Http\Middleware\LogAfterRequest::class,
        'auth.token' => \App\Http\Middleware\CheckToken::class,
        'tna' => \Platform\TNA\Middlewares\TNAExist::class,
        'tnaItem' => \Platform\TNA\Middlewares\TNAItemExist::class,
        'taskExist' => \Platform\Tasks\Http\Middlewares\TaskExist::class,
        'taskEligibility' => \Platform\Tasks\Http\Middlewares\TaskCheckEligible::class
    ];
}
