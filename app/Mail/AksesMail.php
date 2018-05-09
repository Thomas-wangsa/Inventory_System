<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Http\Models\Akses_Data;

class AksesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demo;
    public $pathToImage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
        $this->pathToImage = public_path()."/images/logo/google.png";
    
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        //return $this->view('view.name');
        // return $this->from('sender@example.com')
        //             ->view('mails.demo')
        //             ->text('mails.demo_plain')
        //             ->with(
        //               [
        //                     'testVarOne' => '1',
        //                     'testVarTwo' => '2',
        //               ]);


        return $this->view('mails.demo');

        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                    ->addTextHeader('Custom-Header', 'HeaderValue');
        });

    }
}
