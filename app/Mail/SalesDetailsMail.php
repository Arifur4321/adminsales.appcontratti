<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('You are registered as new sales')
                    ->view('emails.sales_details')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);
    }
}
