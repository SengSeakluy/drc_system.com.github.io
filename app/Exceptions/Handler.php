<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        /**
         * response as json for incorrect route method of request api.
         * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
         */
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            //
            if(explode('/', $request->getRequestUri())[1] == 'api') {
                return response()->json(['message' => $e->getMessage()], 405, []);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            //
            if(str_contains($request->header('User-Agent'), 'PostmanRuntime') == true) {
                return response()->json(['message' => 'Invalid endpoint'], 404, []);
            }
        });
    }

    // public function render($request)
    // {
    //     if($exception instanceof CustomException) {
    //         return $this->showCustomErrorPage();
    //     }

    //     return parent::render($request, $exception);
    // }
}
