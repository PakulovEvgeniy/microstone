<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;
    public $dialog;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $dialog)
    {
        $this->token = $token;
        $this->dialog = $dialog;
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
        $m = false;
        if ($this->dialog) {
           $m = (new MailMessage)
            ->subject('Уведомление о изменении пароля')
            ->line('Вы получили это сообщение, потому что сделали запрос на изменение пароля к вашему аккаунту.')
            ->line('Скопируйте этот токен в форму на сайте.')
            ->line(new HtmlString('<b>' . $this->token . '</b>'))
            ->line(Lang::getFromJson('Срок действия токена для изменения пароля истекает через :count минут.', ['count' => config('auth.passwords.users.expire')]))
            ->line('Если письмо пришло Вам по ошибке, проигнорируйте его.');
        } else {
           $m = (new MailMessage)
            ->subject('Уведомление о изменении пароля')
            ->line('Вы получили это сообщение, потому что сделали запрос на изменение пароля к вашему аккаунту.')
            ->action('Изменить пароль', url(config('app.url').route('password.reset', ['token' => $this->token], false)))
            ->line(Lang::getFromJson('Срок действия ссылки для изменения пароля истекает через :count минут.', ['count' => config('auth.passwords.users.expire')]))
            ->line('Если письмо пришло Вам по ошибке, проигнорируйте его.'); 
        }

        return $m;
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
