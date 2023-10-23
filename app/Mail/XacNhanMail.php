<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class XacNhanMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $info;

    public function __construct($info)
    {
        $this->info = $info;
    }

    public function build()
    {
        return $this->subject('Xác nhận thanh toán tự động thành công')
                    ->view('mail.thong_bao_thanh_toan', [
                        'info'      =>  $this->info,
                    ]);
    }
}
