<?php namespace App\Http\Middleware;

use App\Http\Controllers\ApiController;
// use App\Repositories\Contracts\AuthRepository;
use Closure;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;


class Cors
{



    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
        if ( $request->getMethod() == "OPTIONS") {
            $headers = array(
                'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
                'Access-Control-Allow-Origin'=> $origin,
                'Access-Control-Allow-Headers'=> 'X-Requested-With, content-type, accept, Authorization, X-Auth-Token, If-None-Match , If-Match, access-token',
                'Access-Control-Allow-Credentials'=> 'true'
            );
            return Response::make('', 200, $headers);
        }
        header('Access-Control-Expose-Headers: ETag');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: $origin");
        header_remove('X-Powered-By');
        header('Content-Type: application/json');
        header('Connection: close');
        return $next( $request );
    }

}
