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
			$t_n = '';
			if ($val->type == 'u') {
				$t_n = 'Юридическое лицо';
			} elseif ($val->type == 'p') {
				$t_n = 'Индивидуальный предприниматель';
			} elseif ($val->type == 'f') {
				$t_n = 'Физическое лицо';
			}
			$nam = $val->name;
			if ($val->type == 'f') {
				$nam = str_replace('#-#', ' ', $nam);
			}
    		$res[] = [
    			'id' => $val->id,
    			'user_id' => $val->user_id,
				'type' => $val->type,
				'type_text' => $t_n,
    			'inn' => $val->inn,
    			'kpp' => $val->kpp,
    			'name' => $nam,
    		];
    	}
    	return $res;
    }
}
