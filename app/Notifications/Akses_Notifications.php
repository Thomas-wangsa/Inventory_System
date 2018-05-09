<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
            "to"            => $this->param['head'],
            "from"          => $this->param['staff'],
            "desc"          => $this->param['desc'],
            "url"           => config('app.url'),
            "url1"          => config('app.url')."/akses_approval?uuid=".$this->param['uuid'],
            "url2"          => config('app.url')."/akses_reject?uuid=".$this->param['uuid'],
            "nama_user"     => $this->param['nama_user'],
            "divisi_user"   => $this->param['divisi'],
            "ktp"           => $this->param['ktp'] != null ? $this->param['ktp'] : null
        );
        return (new MailMessage)
                    ->subject($this->param['subject'])
                    ->markdown('vendor.notifications.akses_notification', ['data' => $data]);
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
