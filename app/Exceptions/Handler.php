<?php

namespace App\Exceptions;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use League\Fractal\Manager;
use Platform\HttpLogs\Logs\ExceptionMessageAcceptor;
use Platform\App\Validation\DataValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Platform\App\Exceptions\SeException;
use Raven_Client;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		HttpException::class,
		ModelNotFoundException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{ 
		if (env('APP_ENV') == 'production') {
			$client = new Raven_Client('https://f7c8cacb20714e86bc82976de1b5ec8a:b1afa6cf73374e238afef5836a51ae7d@app.getsentry.com/66168');
			$handler = new \Monolog\Handler\RavenHandler($client);
			$handler->setFormatter(new \Monolog\Formatter\LineFormatter("%message% %context% %extra%\n"));
			$monolog = $this->log->getMonolog();
			$monolog->pushHandler($handler);
		}
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		$apiController =  new ApiController(new Manager());

		if ($e instanceof ModelNotFoundException) {
			$this->getStatusMessage($request,$e);
			$e = new NotFoundHttpException($e->getMessage(), $e);
		}

		if ($e instanceof NotFoundHttpException) {
			$this->getStatusMessage($request,$e);
			return $apiController->setStatusCode(404)
					->respondWithError('Page/Resource Not Found', 'SE_40004');
		}

		if ($e instanceof DataValidationException) {
			$this->getStatusMessage($request,$e);
			return $apiController->setStatusCode(422)
					->respondWithError($e->getMessage(), 'SE_3210110');
		}

		if ($e instanceof SeException) {
			$this->getStatusMessage($request,$e);
			return $apiController->setStatusCode($e->getHttpStatusCode())
					->respondWithError($e->getSeMessage(), $e->getSeStatusCode());
		}

		if (app()->environment() == 'production') {
			$this->getStatusMessage($request,$e);
			return $apiController->setStatusCode(500)
					->respondWithError('Internal Server Error', 'SE_50000');
		}
		$this->getStatusMessage($request,$e);
		return parent::render($request, $e);
	}

	public function getStatusMessage($request, $e){

		$exceptionMessage = new ExceptionMessageAcceptor();
		$exceptionMessage->getExceptionMessage($request, $e);
	}
}
