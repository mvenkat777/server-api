<?php

namespace App\Http\Middleware;

use Closure;
use League\Fractal\Manager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Authentication\Commands\ValidateTokenCommand;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var DefaultCommandBus
     */
    protected $commandBus;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth, DefaultCommandBus $commandBus)
    {
        $this->auth = $auth;
        $this->commandBus = $commandBus;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('access-token');
        $validated = $this->commandBus->execute(new ValidateTokenCommand($token));
        if ($validated) {
            return $next($request);
        } else {
            return (new ApiController(new Manager()))->setStatusCode(401)->respondWithError('Unauthorized', 'SE_3210115');
        }
    }
}
