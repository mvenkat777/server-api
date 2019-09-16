<?php

namespace Platform\App\Http\Middlewares;

use Closure;
use Carbon\Carbon;

class EtagsMiddleware
{
   public function handle($request, Closure $next)
   {
       $response = $next($request);

       if($request->isMethod('get') && isset($response->getData()->data)) {
           $etag = md5(json_encode($response->getData()->data));
           $requestEtag = str_replace('"', '', $request->getEtags());

           if($requestEtag && $requestEtag[0] == $etag) {
               $response->setNotModified();
               $response->setEtag($etag);
               $response->header('Cache-Control', 'max-age=120, private');
               return $response;
           }
           $response->setEtag($etag);
           $response->setLastModified(Carbon::now());
           $response->setExpires(Carbon::tomorrow());
           $response->header('Cache-Control', 'max-age=120, private');
       }

       return $response;
   }
}
/*
class EtagsMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if($request->isMethod('get') && isset($response->getData()->data)) {
            $etag = md5(json_encode($response->getData()->data));
            $requestEtag = str_replace('"', '', $request->getEtags());

            if($requestEtag && $requestEtag[0] == $etag) {
                $response->setNotModified();
                return $response;
            }
            $response->setEtag($etag);
            $response->setLastModified(Carbon::now());
            $response->setExpires(Carbon::tomorrow());        
            $response->header('Cache-Control', 'max-age=0');
        }

        return $response;
    }
}
*/
