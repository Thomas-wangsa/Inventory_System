<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
class Akses_Notifications extends Notification
{
    use Queueable;

    protected $param;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($param)
    {   
        $this->param = $param;
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
        $data = array(
            "description"            => $this->param['description'],
            "access_card_name"  => $this->param['access_card_name'],
            "access_card_no"    => $this->param['access_card_no'],
            "status_akses"      => $this->param['status_akses'],
            "status_color"      => $this->param['status_color'],
            "url"               => URL::to('/'),
            "url_data"          => URL::to('/').
                                "/accesscard?search=on&search_uuid=".$this->param['uuid'],
            
            
            
            
        );
        return (new MailMessage)
                    ->from("no_reply@gmail.com","Indosat-System")
                    ->replyTo("no_reply@gmail.com")
                    ->subject($this->param['subject'])
                    ->markdown('vendor.notifications.akses_notification_new', ['data' => $data])
                    ->cc($this->param['cc_email']);
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
        ];
    }
}
