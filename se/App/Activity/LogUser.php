<?php

namespace Platform\App\Activity;

class LogUser
{
    private $user;

    private $email;

    public function log($request, $response)
    {
        if(strtolower($request->method()) == 'options') {
            return;
        }

        try {
            //if user is valid user
            if(!is_null(\Auth::user())) {
                $this->userId = \Auth::user()->id;
                $this->email = \Auth::user()->email;
            } else {
                $this->userId = 'anonymous';
                $this->email = 'anonymous';
            }

            $data = [
                'userId' => $this->userId,
                'email' => $this->email,
                'app' => $this->getRequestedApp($request, $response),
                'requestUrl' => $request->path(),
                'requestType' => strtolower($request->method()),
                'statusCode' => $response->status(),
                'accessToken' => $request->header('access-token')
            ];
            return \Platform\App\Activity\Models\LogUser::create($data);
        } catch(\Exception $e){
        }
    }

    private function getRequestedApp($request, $response)
    {
        if($response->status() === 201) {
            $paths = explode('/', $request->path());
            return rtrim($paths[count($paths) - 1], 's');
        }
        return rtrim(explode('/', $request->path())[0], 's');
    }
}
