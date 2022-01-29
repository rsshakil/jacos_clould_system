<?php

namespace App\Exceptions;

// use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
// use Illuminate\Http\Request;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Illuminate\Support\Facades\Mail;
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
     */
    public function report(Throwable $exception)
    {
        // emails.exception is the template of your email
             // it will have access to the $error that we are passing below

            if ($this->shouldReport($exception)) {
                if (config('const.SEND_EXCEPTION')==1) {
                    $this->sendEmail($exception); // sends an email
                }

            }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof \Illuminate\Session\TokenMismatchException)
        {
            Log::info($exception->getMessage());
            return redirect('/login')->with('message', 'Your token has been expired here. Please relogin');
            // return back()->with('message', 'Your message here.');
        }
        if ($exception->getCode()===419) {
            return redirect('/login');
            // return response()->json(['status'=>$exception->getCode()]);
        }
        return parent::render($request, $exception);
    }

    public function sendEmail(Throwable $exception)
    {
        try {
            $e = FlattenException::create($exception);
            $handler = new HtmlErrorRenderer(true); // boolean, true raises debug flag...
            $css = $handler->getStylesheet();
            $content = $handler->getBody($e);
            Log::info('send mail');
            Mail::send('emails.exception', compact('css','content'), function ($message) {
                $message->to(config('const.EXCEPTION_SEND_MAILS'))
                                    ->subject('Exception: error');
            });
            Log::info('excp end');
        } catch (Throwable $exception) {
            Log::info('send not mail');
            Log::error($exception);
        }
    }

    public function sendEmail_backup(Throwable $exception)
    {
       try {
            $e = FlattenException::create($exception);
            $handler = new HtmlErrorRenderer(true); // boolean, true raises debug flag...
            $css = $handler->getStylesheet();
            $content = $handler->getBody($e);
            Log::info('send mail');
            Log::info('ecp start');
            Log::info($exception);
            Log::info('excp end');
            Mail::send('emails.exception', compact('css','content'), function ($message) {
                $message->to(config('const.EXCEPTION_SEND_MAILS'))
                                    ->subject('Exception: ' . Request::fullUrl());
            });
        } catch (Throwable $exception) {
            Log::info('send not mail');
            Log::error($exception);
        }
    }
}
