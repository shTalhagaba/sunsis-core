<?php

namespace App\Mail;

use App\Facades\AppConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionOccured extends Mailable
{
    use Queueable, SerializesModels;

    public $exception;
	public $extra;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $postData = "";
        if(in_array(request()->method(), ["PATCH", "POST"]))
        {
            foreach(request()->all() as $key => $val)
            {
                $postData .= $key." = ".$val."\r\n\r\n";
            }
        }

        $this->extra['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH).'?'.parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY)) : "";
        $this->extra['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
        $this->extra['REMOTE_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

        $subject = AppConfig::get('FOLIO_CLIENT_NAME') . ': Exception Message';

        $mail = $this->subject($subject)
            ->view('emails.exception')
            ->with([
                'exception' => $this->exception,
                'extra' => $this->extra,
            ]);

        if ($postData != "") 
        {
            $mail->attachData($postData, 'POST.txt', [
                'mime' => 'text/plain',
            ]);
        }

        return $mail;
    }
}
