<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\TerminableMiddleware;
use Platform\HttpLogs\Logs\HttpLog;
use Illuminate\Support\Facades\Queue;


class LogAfterRequest extends HttpLog implements TerminableMiddleware 
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
        return $next($request);
    }   
    /**
     * Capture logs on completion of any http request reponse cycle
     * @param  $request contains all the request parameters and headers 
     * @param  $response contains all the response data
     * @return [type]           [description]
     */
    public function terminate($request, $response)
    {
        (new \Platform\App\Activity\LogUser)->log($request, $response);
        $this->logLineData($this->getRequestResponseData($request, $response));  
    }

    
}
