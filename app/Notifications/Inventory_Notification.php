<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        //dd($this->param);
        $data = array(
            "to"            => $this->param['head'],
            "from"          => $this->param['staff'],
            "desc"          => $this->param['desc'],
            "url"           => config('app.url'),
            "url1"          => config('app.url')."/inventory_approval?uuid=".$this->param['uuid'],
            "url2"          => config('app.url')."/inventory_reject?uuid=".$this->param['uuid'],
            "nama_barang"     => $this->param['nama_barang'],
            "kategori"   => $this->param['kategori'],
            "count"           => $this->param['count'] != null ? $this->param['count'] : null
        );
        //echo "email masuk";
        //dd($data);
        return (new MailMessage)
                    ->subject($this->param['subject'])
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
