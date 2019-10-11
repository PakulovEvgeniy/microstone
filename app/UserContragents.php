<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContragents extends Model
{
    //
	protected $table = 'user_contragents';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getContragents($user) {
    	$row = UserContragents::where('user_id', $user->id)->get();
    	$res = [];
    	foreach ($row as $val) {
    		$res[] = [
    			'id' => $val->id,
    			'user_id' => $val->user_id,
    			'type' => $val->type,
    			'inn' => $val->inn,
    			'kpp' => $val->kpp,
    			'name' => $val->name,
    		];
    	}
    	return $res;
    }
}
