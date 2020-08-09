<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class VerifyEmailOrder extends Notification
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;
    public $ext_data;


    public function __construct($ext_data)
    {
        $this->ext_data = $ext_data;
    }
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }
        $m = (new MailMessage)
            ->subject(Lang::getFromJson('Подтверждение Email'))
            ->line(Lang::getFromJson('Вы оформляете заказ на нашем сайте.'))
            ->line(Lang::getFromJson('Для подтверждения e-mail адреса введите, указанный ниже код, в форме на сайте.'))
            ->line(new HtmlString('<b>' . $this->ext_data['cod'] . '</b>'));
        if ($this->ext_data['password']) {
            $m = $m->line(Lang::getFromJson('Для доступа на сайт вам назначен временный пароль. Не забудьте его поменять на свой в вашем профиле!'))
            ->line(new HtmlString($this->ext_data['password']));
        }
            
        $m = $m->line(Lang::getFromJson('Если письмо пришло Вам по ошибке, и вы не регистрировались на нашем сайте, проигнорируйте его.'));
        return $m;
    }

    
    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
