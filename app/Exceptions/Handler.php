<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->handleValidationError($request, $exception);
        }
        
        if ($exception instanceof \Symfony\Component\ErrorHandler\Error\FatalError ) { 
        }

        return response()->json([
            'status'    => 'error', 
            'message'   => $exception->getMessage(), 
            'line'      => $exception->getLine(),
            'file'      => $exception->getFile(),
            'trace'     => $exception->getTraceAsString(), 
            'code' => 500,
        ], 500);
        // return parent::render($request, $exception);
    }

    /**
     * Handle validation exception
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function handleValidationError($request, \Illuminate\Validation\ValidationException $exception)
    {
        $errors = $exception->validator->errors();
        $status = $exception->status;

        return response()->json([
            'status'    => 'error', 
            'message'   => $errors->first(), 
        ], 200);
    }
}
