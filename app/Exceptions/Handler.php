<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // return response()->json(
        //     [
        //         'errors' => [
        //             'status' => 401,
        //             'message' => 'Unauthenticated',
        //         ]
        //     ], 401
        // );
        if ($exception instanceof Exception && !($exception instanceof AuthenticationException)) {
            $errorExceptionMessage = sprintf('%s in %s line %s : %s',class_basename($exception),basename($exception->getFile()),$exception->getLine(),$exception->getMessage());
            if ($request->is('api/*')) {
               return response()->json([
                   'status' => false,
                   'status_code' => 500,
                   'error' => 'Someting wrong, please try after some time.',
                   'error_code' => 'exception_found',
                   'exception' => $errorExceptionMessage,
               ],500);
            }  
            else
            {
                $environmentsArr = ['production'];
                if (config('app.debug') && (in_array(config('app.env'),$environmentsArr)) ) {
                    // die($errorExceptionMessage);
                }
            }
        }
        return parent::render($request, $exception);
    }
}
