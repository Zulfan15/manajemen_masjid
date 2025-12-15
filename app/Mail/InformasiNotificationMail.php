<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformasiNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $content;
    public $url;

    public function __construct($title, $content, $url)
    {
        $this->title   = $title;
        $this->content = $content;
        $this->url     = $url;
    }

    public function build()
    {
        return $this->subject("Informasi Terbaru: {$this->title}")
                    ->view('modules.informasi.emails.informasi');
    }
}
