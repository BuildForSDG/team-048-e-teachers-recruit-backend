<?php

namespace App\Exceptions;

use App\Services\ResponseService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
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
        if($exception instanceof ValidationException) {
            //transform error validation
            $error = $exception->errors();
            return (new ResponseService())->getErrorResource([
                //get first message of the error
                "message" => $error[key($error)][0],
                "field_errors" => $error
            ]);
        }

        if ($exception instanceof NotFoundHttpException) {

            if($request->path() != '/' || !empty($request->query())){
                return response()->json(['error' => 'Not Found'], 404);
            }

        }
        return parent::render($request, $exception);
    }

}
