<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompareGroups extends Model
{
    protected $table = 'compare_groups';
    public $timestamps = false;
    protected $fillable = ['id_1s'];
}
