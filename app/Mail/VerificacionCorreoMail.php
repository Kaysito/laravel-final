<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificacionCorreoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $codigo;
    public $pwdTemporal;

    public function __construct($usuario, $codigo, $pwdTemporal)
    {
        $this->usuario = $usuario;
        $this->codigo = $codigo;
        $this->pwdTemporal = $pwdTemporal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido al Sistema - Código de Verificación',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verificacion', 
        );
    }

    public function attachments(): array
    {
        return [];
    }
}