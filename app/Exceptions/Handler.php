<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use PDOException;
use Spatie\Permission\Exceptions\UnauthorizedException;
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

    }

    public function render($request, Throwable $exception)
    {
        // does this request asks for json?
        if ($request->ajax() ) {
            // do we have exception triggered for this response?
            if (!empty($exception)) {

                // set default error message
                $response = [
                    'error' => 'Error de Request'
                ];
                $status = 400;
                // get correct status code

                if ($exception instanceof ValidationException) {

                    return $this->convertValidationExceptionToResponse($exception, $request);

                } else if ($exception instanceof AuthorizationException) {
                    $status = 403;
                    $response['status'] = 'error';
                    $response['message'] = 'No cuenta con autorizacion para utilizar esta funcion!';
                    //is it DB exception

                } else if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                    $status = 401;
                    $response['status'] = 'error';
                    $response['message'] = 'No cuenta con una sesion activa!';
                    //is it DB exception
                } else if ($exception instanceof PDOException) {
                    $status = 500;
                    $response['status'] = 'error';
                    $response['message'] = 'Ah ocurrido una excepcion en la base de datos!';
                    // is it http exception (this can give us status code)
                } else if ($this->isHttpException($exception)) {
                    $status = $exception->getStatusCode();
                    $response['status'] = 'error';
                    $response['message'] = 'Formato de request Invalida!';

                } else if ($exception instanceof UnauthorizedException) {
                    $response['status'] = 'error';
                    $response['message'] = 'No cuentas con los permisos requeridos';
                    $status = 403;
                } else {
                    // for all others check do we have method getStatusCode and try to get it
                    // otherwise, set the status to 400
                    $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400;
                    $response['message'] = 'No se puede procesar tu request';
                }
                // If debug mode is enabled
                if (config('app.debug')) {
                    $response['exception'] = get_class($exception); // Reflection might be better here
                    $response['trace'] = $exception->getTrace();
                }

                return response()->json($response, $status);
            }
        }
        return parent::render($request, $exception);
    }

}
