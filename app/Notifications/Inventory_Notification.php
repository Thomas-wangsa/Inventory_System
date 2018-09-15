<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class Inventory_Notification extends Notification
{
    use Queueable;

    public $param;

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
            "desc_1"            => $this->param['desc_1'],
            "desc_name"         => $this->param['desc_name'],
            "desc_2"            => $this->param['desc_2'],
            "description"       => $this->param['description'],
            "status_inventory"  => $this->param['status_inventory'],
            "status_color"      => $this->param['status_color'],
            "url"               => URL::to('/'),
            "url_data"          => URL::to('/').
                                "/inventory?search=on&search_uuid=".$this->param['uuid'],
            "url_reject"        => URL::to('/').
                                "/inventory_reject?uuid=".$this->param['uuid']              
        );


        if(count($this->param['map_location']) < 1) {
            $data['map_location'] = null;
        } else {
            $data['map_location'] = URL::to('/')."/map/view_map".
                        "?uuid=".$this->param['uuid'].
                        "&map_location_uuid=".$this->param['map_location']->map_location_uuid;
        }

        // dd($data);
        return (new MailMessage)
                    ->from("no_reply@gmail.com","Indosat-System")
                    ->replyTo("no_reply@gmail.com")
                    ->subject($this->param['subject'])
                    ->cc($this->param['cc_email'])
                    ->markdown('vendor.notifications.inventory_notification', ['data' => $data]);
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
