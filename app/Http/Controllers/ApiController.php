<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class ApiController.
 */
class ApiController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var null
     */
    protected $hint = null;

    /**
     * @var int
     */
    protected $version = 3;

    protected $notification = [
                    'type' => 'success',
                    'seCode' => 'SE_20000',
                    'message' => 'Success',
                    'hint' => '',
                ];

    /**
     * @var null
     */
    protected $validationError = null;
    /**
     *
     */
    const HTTP_OK = 200;

    /**
     *
     */
    const HTTP_CREATED = 201;

    /**
     *
     */
    const HTTP_NOT_MODIFIED = 304;

    /**
     *
     */
    const HTTP_BAD_REQUEST = 400;

    /**
     *
     */
    const HTTP_UNAUTHORIZED = 401;

    /**
     *
     */
    const HTTP_FORBIDDEN = 403;

    /**
     *
     */
    const HTTP_NOT_FOUND = 404;

    /**
     *
     */
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    protected $fractal;

     /* @param Manager $fractal
     */
    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
        $this->fractal->parseIncludes(explode(',', Input::get('include')));
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $hint
     *
     * @return $this
     */
    public function setHint($hint)
    {
        $this->notification['hint'] = $hint;
        return $this;
    }

    /**
     * @return string
     */
    public function getHint()
    {
        return $this->notification['hint'];
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->notification['message'] = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->notification['message'];
    }

    /**
     * @param int $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $type
     * @param string $seCode
     * @param string $message
     * @param null   $hint
     * @param null   $executionTime
     *
     * @return $this
     */
    public function setNotification($type, $seCode, $message, $hint = null, $executionTime = null)
    {
        if (is_null($hint)) {
            $hint = $this->hint;
        }
        $this->notification['type'] = (string) $type;
        $this->notification['seCode'] = (string) $seCode;
        $this->notification['message'] = $message;
        $this->notification['hint'] = $hint;

        return $this;
    }

    /**
     * @return array
     */
    public function getNotification()
    { //dd($this->notification);
        return $this->notification;
    }

    /**
     * @param $errors
     *
     * @return $this
     */
    public function setValidationError($errors)
    {
        $this->validationError = $errors;

        return $this;
    }

    /**
     * @param $item
     * @param $callback
     * @param string $namespace
     *
     * @return mixed
     */
    public function respondWithNewItem($item, $callback, $namespace = 'data')
    {
        $this->setStatusCode(201);

        return $this->respondWithItem($item, $callback, $namespace);
    }

    /**
     * @param $item
     * @param $callback
     * @param string $namespace
     *
     * @return mixed
     */
    public function respondWithItem($item, $callback, $namespace = 'data')
    {
        $resource = new Item($item, $callback, $namespace);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param $collection
     * @param $callback
     * @param string $namespace
     *
     * @return mixed
     */
    public function respondWithPaginatedCollection($collection, $callback, $namespace = 'data')
    {
        $resource = new Collection($collection, $callback, $namespace);

        $queryParams = array_diff_key($_GET, array_flip(['page']));
        foreach ($queryParams as $key => $value) {
            $collection->addQuery($key, $value);
        }

        $paginatorAdapter = new IlluminatePaginatorAdapter($collection);
        $resource->setPaginator($paginatorAdapter);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    public function respondWithCollection($collection, $callback, $namespace = 'data')
    {
        $resource = new Collection($collection, $callback, $namespace);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param array $array
     * @param array $headers
     *
     * @return mixed
     */
    protected function respondWithArray(array $array = ['data' => []], array $headers = [])
    {
        if (isset($array['data']['selfLink'])) {
            $array['link'] = $array['data']['selfLink'];
        }
        $array['code'] = $this->getStatusCode();
        $array['notification'] = $this->getNotification();
        $array['version'] = $this->getVersion();
        $et = getenv('ET');
        // $array['notification']['executionTime'] = json_decode($et);
        $total = 0;

        if(!is_null(json_decode($et))){
            foreach (json_decode($et) as $key => $value) {
                $total += $value->time;
            }
            $array['notification']['totalExecutionTime'] = ($total / 1000).' sec';
        }

        return Response::json($array, $this->statusCode, $headers);
    }

    /**
     * @param $message
     * @param null $seCode
     *
     * @return mixed
     */
    public function respondWithError($message, $seCode = null)
    {
        if ($this->statusCode == 200 || $this->statusCode == 201) {
            $this->setStatusCode(500);
        }

        if (!$seCode) {
            $seCode = 'SE_'.$this->getStatusCode();
        }

        return $this->setNotification('error', $seCode, $message)
                    ->respondWithArray();
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondOk($message = 'Ok')
    {
        return $this->setNotification('success', 'SE_20000', $message)
                    ->respondWithArray();
    }

    /**
     * @param $e
     *
     * @return mixed
     */
    protected function respondError($e)
    {
        switch (substr($e->getCode(), 0, 3)) {
            case 304:
                return $this->respondNotModified($e->getMessage(), $e->getCode());
                break;
            case 400:
                return $this->respondBadRequest($e->getMessage(), $e->getCode());
                break;
            case 403:
                return $this->respondForbiddenError($e->getMessage(), $e->getCode());
                break;
            case 404:
                return $this->respondNotFound($e->getMessage(), $e->getCode());
                break;
            default:
                return $this->respondInternalError($e->getMessage(), $e->getCode());
                break;
        }
    }

    /**
     * @param string $message
     * @param int    $errorCode
     *
     * @return mixed
     */
    public function respondNotFound($message = 'Not found', $errorCode = self::HTTP_NOT_FOUND)
    {
        return $this->setStatusCode(self::HTTP_NOT_FOUND)->respondWithError($message, $errorCode);
    }

    /**
     * @param string $message
     * @param int    $errorCode
     *
     * @return mixed
     */
    public function respondBadRequest($message = 'Bad request', $errorCode = self::HTTP_BAD_REQUEST)
    {
        return $this->setStatusCode($errorCode)->respondWithError($message, $errorCode);
    }

    /**
     * @param string $message
     * @param int    $errorCode
     *
     * @return mixed
     */
    public function respondNotModified($message = 'Not modified', $errorCode = self::HTTP_NOT_MODIFIED)
    {
        return $this->setStatusCode(self::HTTP_OK)->respondWithError($message, $errorCode);
    }

    /**
     * @param string $message
     * @param int    $errorCode
     *
     * @return mixed
     */
    public function respondInternalError($message = 'Internal server error', $errorCode = self::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->setStatusCode(self::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message, $errorCode);
    }

    /**
     * @param string $message
     * @param int    $errorCode
     *
     * @return mixed
     */
    public function respondUnauthorizedError($message = 'Unauthorized', $errorCode = self::HTTP_UNAUTHORIZED)
    {
        return $this->setStatusCode(self::HTTP_UNAUTHORIZED)->respondWithError($message, $errorCode);
    }

    /**
     * @param string $message
     * @param int    $errorCode
     *
     * @return mixed
     */
    public function respondForbiddenError($message = 'Forbidden', $errorCode = self::HTTP_FORBIDDEN)
    {
        return $this->setStatusCode(self::HTTP_FORBIDDEN)->respondWithError($message, $errorCode);
    }

    /**
     * @param $data
     * @param null $rules
     *
     * @return bool
     */
    public function isValid($data, $rules = null)
    {
        if (!$rules) {
            $class_name = implode('', array_slice(explode('\\', get_class($this)), -1));
            $class_name = '\\App\\'.str_replace('Controller', '', $class_name);
            $rules = $class_name::$rules;
        }
        $validator = \Illuminate\Support\Facades\Validator::make($data, $rules);

        if ($validator->fails()) {
            $this->validationError = $validator->messages();

            return false;
        }

        return true;
    }

    /**
     * @param $uuid
     *
     * @return bool
     */
    public function isValidUUID($uuid)
    {
        //dd(array_keys($uuid)[0]);
        // if (!$this->isValid($uuid, [array_keys($uuid)[0] => 'required|regex:/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/'])) {
        //     return false;
        // }

        return true;
    }
}
