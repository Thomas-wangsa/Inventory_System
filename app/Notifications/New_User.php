<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class New_User extends Notification
{
    use Queueable;

    protected $password;
    protected $user_email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password,$user_email)
    {
        $this->password = $password;
        $this->user_email = $user_email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        return (new MailMessage)
                    ->from("no_reply@gmail.com","Indosat-System")
                    ->replyTo("no_reply@gmail.com")
                    ->subject('Welcome to Indosat System')
                    ->greeting("Hello ".$this->user_email)
                    ->line("Your account has been registered of our system with password : $this->password")
                    ->action("Log in", url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
