<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParamTypes extends Model
{
    protected $table = 'param_type';
    protected $fillable = ['id'];
    public $timestamps = false;
}
