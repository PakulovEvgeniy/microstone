<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = ['id_1s'];

    public function category_description()
    {
        return $this->hasOne('App\Category_description');
    }
}
