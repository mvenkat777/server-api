<?php

namespace Platform\HttpLogs\Logs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Platform\HttpLogs\Logs\ExceptionMessageAcceptor;
use Carbon\Carbon;
use Platform\HttpLogs\Logs\IpLogs;
use Platform\HttpLogs\Repositories\Contracts\LogRepository;
use Exception;

class HttpLog extends IpLogs
{	

  public $remoteStatus;

  /**
     * Fetch relevant data from request, response and organize them 
     * @param  $request contains all the request parameters and headers 
     * @param  $response contains all the response data
     * @return $lineData organized data
     */
	protected function getRequestResponseData($request, $response)
    {
      $data = [ 
                'type'=> "framework",
                'createdAt'=> $this->getLocalTime(),
                'logType'=> "",
                'app'=> "API", 
                'UA' => $this->getUserAgent($request),
                'request' => [  'type' => $this->getRequestMethod($request) , 
                                'port' => $this->getRemotePort($request),
                                'path' => $this->getRequestedUrlPath($request),
                                //'location' => [ 'name' => $this->getUserLocation($request),
                                                //  'code' => $this->getUserLocation($request),
                                                //  'latlog' => ""
                                                //  ] ,
                'ip' => $this->getUserIpAddress($request) ,
                'requesterID' => $this->getRequestUserId() ,
                'postData' => $this->postRequestData($request),
                'contentLength' => $this->getContentLength($request)
              ],         
              'response' => [ 
                              'message' => $this->getResponseMessage($response),        
                              'status' => $this->getRequestResponseStatus($response)
                            ]
                ];
        
        return $data;
    }

    protected function getOnlyRequestExceptionData($request, $e)
    {
      $data = [  
                'type'=> "framework",
                'createdAt'=> $this->getLocalTime(),
                'logType'=> "",
                'app'=> "API" ,
                'UA' => $this->getUserAgent($request) ,
                'request' => [  
                                'type' => $this->getRequestMethod($request) , 
                                'port' => $this->getRemotePort($request),
                                'path' => $this->getRequestedUrlPath($request),
                                // 'location' => [ 
                                //                 'name' => '',
                                //                 'code' => '',
                                //                 'latlog' => ""
                                //               ] ,
                'ip' => $this->getUserIpAddress($request) ,
                'requesterID' => $this->getRequestUserId() ,
                'postData' => $this->postRequestData($request),
                'contentLength' => $this->getContentLength($request)
              ],         
              'response' => [ 
                              'message' => $this->getResponseMessage($e),       
                              'status' => $this->getRequestResponseStatus($e)
                            ]      
          ];
      return $data;
    }
    /**
     * The variable data will be written into the log file 
     * @param  $lineData contains the stored data
     */
    protected function logLineData($content)
    {
      //dd($content);
      $httpLog = new Logger('ALERT');
      $logFile = storage_path()."/logs/LoggerInfo/logs/api-http-".Carbon::now()->format('Y-m-d').".log";
      $httpLog->pushHandler(new StreamHandler($logFile, Logger::INFO));
      $httpLog->addInfo(json_encode($content));
      // $get = $this->getFailedLoginRequestIp(); // Code to read a file to get the list of failed or successful Ip addresses.
      // if(count($get['Failed']) != 0){
      //   for($count =0; $count < count($get['Failed']); $count++){
          
      //     echo $get['Failed'][$count]."\n";
      //     $data1 = $this->getLogData($get, $content);
      //     dd($data1);
      //     $this->logRepository->saveInDatabase($data1);
      //   }
      // }
      // dd("ll");
      // // dd($get['Failed'][0]);
    }

    // private function getLogData($get, $content){
    //   return [
    //     'userId' => $content[1]['UserId'],
    //     'ipAddress' => $content[1]['User Ip Address'],
    //     'remoteStatus' => $content[1]['Remote Status'],
    //     'urlPath' => $content[1]['Request Path'],
    //     'requestMethod' => $content[1]['Request Method'],
    //     'userLocation' => $content[1]['User Location']
    //   ];
    // }

    public function getRequestUserId(){
        $getRequestUserId = (isset(Auth::user()->id)) ? $getRequestUserId = Auth::user()->id:'NoUserId';
        return $getRequestUserId;
    }

    public function getUserIpAddress($request){
        return $request->getClientIp();
    }

    public function getLocalTime(){
        return Carbon::now()->format('F j, Y');
    }

    public function getRequestMethod($request){
        return $request->method();
    }

    public function getRequestedUrlPath($request){
        return $request->url();
    }

    public function getRequestResponseStatus($response){
      if (method_exists($response, 'getStatusCode')) {
        return $response->getStatusCode();
      }

      if (method_exists($response, 'getCode')) {
        return $response->getCode();
      }
      return $response->status();
    }

    public function getContentLength($request){
        $remoteStatus = $request->server('CONTENT_LENGTH');
        return $request->server('CONTENT_LENGTH');
    }

    public function getUserAgent($request){
        return $request->header('user-agent');
    }

    public function getUserLocation($request){
      try{
            $ipaddress = '';
           if (getenv('HTTP_CLIENT_IP'))
               $ipaddress = getenv('HTTP_CLIENT_IP');
           else if(getenv('HTTP_X_FORWARDED_FOR'))
               $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
           else if(getenv('HTTP_X_FORWARDED'))
               $ipaddress = getenv('HTTP_X_FORWARDED');
           else if(getenv('HTTP_FORWARDED_FOR'))
               $ipaddress = getenv('HTTP_FORWARDED_FOR');
           else if(getenv('HTTP_FORWARDED'))
               $ipaddress = getenv('HTTP_FORWARDED');
           else if(getenv('REMOTE_ADDR'))
               $ipaddress = getenv('REMOTE_ADDR');
           else
               $ipaddress = 'UNKNOWN';

           if($ipaddress != 'UNKNOWN')
           {
           $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}/json"));
           if($details->ip != NULL){
                if(isset($details->city)){ return $details->city;}
                if(isset($details->country)){ return $details->country;}
                return 'Unable to get Location';
            }
            else{
                    return 'Unable to get Location';
            }
          }
          else{
            return 'Unable to get Location';
          }
        }
        catch(Exception $e){
            return 'Unable to get Location';
        }
    }

    public function getResponseMessage($response){
      if($this->getRequestResponseStatus($response) == 200 && $this->getRequestResponseStatus($response) != 'GET')
      {
        return 'Received Response';
      }
      if (method_exists($response, 'getMessage')) {
        return $response->getMessage();
      }
      else{
          return $response->getContent();
      }
    }

    public function postRequestData($request){
      return $request->all();
    }

    public function getRemotePort($request){
      return $request->server->get('REMOTE_PORT');
    }
}
