<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiltersGroups extends Model
{
    protected $table = 'filters_groups';
    public $timestamps = false;

    public function filters()
    {
        return $this->belongsTo('App\Filters');
    }
}
