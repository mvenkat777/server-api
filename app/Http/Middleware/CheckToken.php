<?php

namespace App\Http\Middleware;

use Closure;
use Platform\Users\Repositories\UserTokenRepository;

class CheckToken
{
    private $userToken;

    public function __construct(UserTokenRepository $userToken)
    {
        $this->userToken = $userToken;
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

        $isValid = $this->userToken->isValid($token);

        if ($isValid) {
            return $next($request);
        }

        return response()->json([
            'Unauthorised.'
        ]);
    }
}
