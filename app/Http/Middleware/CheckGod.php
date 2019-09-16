<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use Platform\Users\Repositories\UserTokenRepository;
use App\Http\Controllers\ApiController;

class CheckGod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->is_god) {
            return $next($request);
        }

        return (new ApiController(new Manager()))->setStatusCode(401)->respondWithError('Unauthorized', 'SE_3210115');
    }
}
