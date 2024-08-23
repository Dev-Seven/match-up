<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Mail;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

// use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionOccured;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        //return parent::report($exception);
        if ($this->shouldReport($exception)) {
            $env_sev = env('APP_ENV');
            $env_debug = env('APP_DEBUG');
            $request = request();
            if ($env_sev != 'local' && $env_debug == false) {
                $this->sendEmail($exception); // sends an email
            } else {
                return parent::report($exception);        
            }
        }
        return parent::report($exception);
    }

    public function sendEmail(Throwable $exception) {
        try {
            $e = FlattenException::create($exception);
            $handler = new HtmlErrorRenderer(true);
            $css = $handler->getStylesheet();
            $content = $handler->getBody($e);

            $env_sev = env('APP_ENV');

            //$url = env('SITE_URL')."=".$request->fullUrl();
            if ($env_sev == 'local') {
                $email = ['chirag.c@upsquare.in'];
            } else {
                $email = ['chirag.c@upsquare.in'];
            }

            Mail::send('emails.exception', compact('css','content'), function ($message) use($email){
                $message->to($email)->subject('Exception: ' . \Request::fullUrl());
            });

            echo json_encode([
                'data' => array(),
                'success' => false,
                'status_code' => 400,
                'message' => 'Something went wrong, try again later'
            ]);
            exit;
        } catch (Exception $ex) {
            dd($ex);
        }
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
        if ($exception instanceof UnauthorizedHttpException) {
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                $message = 'Token Expired';
                return InvalidResponse($message,402);  
            } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                $message = 'Invalid Token';
                return InvalidResponse($message,403);  
            } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                $message = 'Token Blocked';
                return InvalidResponse($message,404);  
            } else {
                $message = 'Token Required';
                return InvalidResponse($message,401);  
            }
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->with('errorMessage', 'This form has expired due to inactivity. Please try again.');
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception) 
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return redirect()->guest('/'); //<----- Change this
    }
}
