<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;

class ApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $club;
    public $color;
    public $logo_url;

    public $title;
    public $from_name;
    public $user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->club = $user->club;
        $this->color = preg_replace("#[^a-zA-Z0-9]#is", "", $user->club->primary_color);
        $this->logo_url = Storage::disk('images')->url($user->club->code . "/defaults/logo.png");

        $this->title = "Retorno de solicitação ao " . $user->club->name;
        $this->from_name = $user->club->name . " - Register";

        $explode_name = explode(" ", $user->name);

        $first_name = $explode_name[0];
        $last_name =  (count($explode_name) > 1) ? end($explode_name) : '';

        $this->user_name = trim($first_name . ' ' . $last_name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@bitnary.com.br', $this->from_name)
            ->subject($this->title)
            ->view('emails.approval-status');
    }
}
