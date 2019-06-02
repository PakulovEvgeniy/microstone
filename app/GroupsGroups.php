<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupsGroups extends Model
{
    protected $table = 'groups_groups';
    public $timestamps = false;

    public function groups()
    {
        return $this->belongsTo('App\Groups');
    }
}
