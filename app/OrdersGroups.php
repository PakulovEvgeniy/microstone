<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersGroups extends Model
{
    protected $table = 'orders_group';
    public $timestamps = false;

    public function orders()
    {
        return $this->belongsTo('App\Orders');
    }
}
