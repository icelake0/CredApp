<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUserOfAlertThresholds extends Notification
{
    use Queueable;

    /**
     * User exchange rates that requires alert base on alert thresholds
     * 
     * @var array
     */
    protected $exchange_rates_requiring_alert;

    /**
     * Create a new notification instance.
     *
     * @param array $exchange_rates_requiring_alert
     * @return void
     */
    public function __construct(array $exchange_rates_requiring_alert)
    {
        $this->exchange_rates_requiring_alert = $exchange_rates_requiring_alert;
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
        $message = (new MailMessage);
        $message->subject("Exchange rate thresholds alert")
            ->greeting("Hello {$notifiable->name}")
            ->line('The following exchange rates are currently below your set thresholds');
        foreach ($this->exchange_rates_requiring_alert as $currency => $rate)
            $message->line("{$currency} : {$rate}");
        $message->line('Thank you for using our application!');
        return $message;
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
