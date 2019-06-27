<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ComentarioNaPostagem extends Notification
{
    use Queueable;

    private $postagem;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($postagem, $user)
    {
        $this->postagem = $postagem;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        /* return ['database', 'email']; */
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user_name = $this->user->name;
        $postagem_id = $this->postagem->id;

        return (new MailMessage)
                    ->line('Sua postagem recebeu um comentário!')
                    ->action('Acessar a postagem', url("/postagens/$postagem_id"))
                    ->line("O usuário $user_name comentou na postagem $postagem_id");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $user_name = $this->user->name;
        $postagem_id = $this->postagem->id;

        $data = [
            'acao' => "O usuário $user_name comentou na postagem $postagem_id",
            'user' => $this->user,
            'postagem' => $this->postagem,
        ];

        return [
            'type' => 'Comentário em postagem',
            'data' => $data,
        ];
    }
}
