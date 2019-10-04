<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPersonal extends Model
{
    protected $table = 'user_personal';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getPersonalData($user) {
    	$row = UserPersonal::where('user_id', $user->id)->first();
    	if ($row) {
    		return [
    			'id' => $user->id,
    			'email' => $user->email,
    			'phone' => $row->phone,
    			'name' => $row->name,
    			'family' => $row->family,
    			'otchestvo' => $row->otchestvo,
    			'nickname' => $row->nickname,
    			'pol' => $row->pol,
    			'bithday' => $row->bithday,
                'sendConfirm' => false
    		];
    	} else {
    		return [
    			'id' => $user->id,
    			'email' => $user->email,
    			'phone' => '',
    			'name' => '',
    			'family' => '',
    			'otchestvo' => '',
    			'nickname' => '',
    			'pol' => '',
    			'bithday' => '',
                'sendConfirm' => false
    		];
    	}
    }
}
