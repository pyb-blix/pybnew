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
    	if($exception instanceOf NotFoundHttpException && $request->segment(1) == ADMIN_URL) {
            return redirect('404');
        }
        elseif($exception instanceOf MethodNotAllowedHttpException) {
            $src_url = $request->src_url;
            if($src_url != '')
                return redirect($src_url);
            return redirect('404');
        }
        else if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->back()->withInput($request->except('password'))->with(['message' => 'Validation Token was expired. Please try again','alert-class' => 'alert-danger']);
        }
        return parent::render($request, $exception);
    }
}