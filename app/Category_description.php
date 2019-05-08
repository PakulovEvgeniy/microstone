<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_description extends Model
{
    protected $table = 'category_description';
    protected $fillable = ['category_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
