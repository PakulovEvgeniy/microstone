<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiltersDefGroups extends Model
{
    protected $table = 'filters_def_groups';
    public $timestamps = false;

    public function filters_def()
    {
        return $this->belongsTo('App\FiltersDef');
    }
}
