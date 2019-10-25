<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    protected $table = 'user_addresses';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getAddresses($user) {
    	$row = UserAddresses::where('user_id', $user->id)->get();
    	$res = [];
    	foreach ($row as $val) {
            $addr = $val->postind . ', ' . $val->region . ', ' . $val->city . ', ' . $val->street . ', ' . $val->house;
            if ($val->dep) {
                $addr = $addr . ', ' . $val->dep;
            }
    		$res[] = [
    			'id' => $val->id,
    			'user_id' => $val->user_id,
    			'address' => $addr,
                'region' => $val->region,
                'city' => $val->city,
                'street' => $val->street,
                'house' => $val->house,
                'dep' => $val->dep,
                'postind' => $val->postind
    		];
    	}

    	return $res;
    }
}
