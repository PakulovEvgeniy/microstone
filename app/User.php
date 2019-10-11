<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use App\Notifications\VerifyEmailCode;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    public $email_ext;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function routeNotificationForMail()
    {
        if ($this->email_ext) {
            return $this->email_ext;
        } else {
            return $this->email;
        }
        
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_personal()
    {
        return $this->hasOne('App\UserPersonal');
    }
    public function user_contragents()
    {
        return $this->hasMany('App\UserContragents');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function sendEmailVerificationNotificationCode($cod, $email)
    {
        $this->email_ext = $email;
        $this->notify(new VerifyEmailCode($cod));
        $this->email_ext = '';
    }
}
