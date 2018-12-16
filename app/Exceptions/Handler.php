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
       // \Illuminate\Notifications\Messages\MailMessage::class;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        if ($exception instanceof \Swift_TransportException ) {
            $redirect = $request->header('referer');
            $request->session()->flash('alert-warning','Failed to create email notification = please check the network connection!, '.$exception->getMessage());
            $request->session()->flash('alert-success','But the application is running well');
            return redirect($redirect."?search=on&search_uuid=".$request->uuid);
        } else if ($exception instanceof \ErrorException) {
            $request->session()->flash('alert-danger','Something wrong = please check the network connection!, '.$exception->getMessage());
            return redirect()->route('home');
        }

        return parent::render($request, $exception);
    }
}
