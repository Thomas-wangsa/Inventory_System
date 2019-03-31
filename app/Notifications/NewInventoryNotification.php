<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class NewInventoryNotification extends Notification
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
            "description"       => $this->param['description'],
            "access_card_name"  => $this->param['access_card_name'],
            "access_card_no"    => $this->param['access_card_no'],
            "status_akses"      => $this->param['status_akses'],
            "status_color"      => $this->param['status_color'],
            "note"              => $this->param['note'],
            "url"               => URL::to('/'),
            "url_data"          => URL::to('/').
                                "/new_inventory?search=on&search_uuid=".$this->param['uuid'],    
        );

        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->markdown('vendor.notifications.new_inventory_notification', ['data' => $data])
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
