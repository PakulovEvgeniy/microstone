<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'brands';
    protected $fillable = ['id'];
    public $incrementing = false;

    public static function getBrands() {
        $rows = Brands::where('status', 1)
          ->orderBy('kod_sort')->get();
        
        $res = [];
        foreach ($rows as $val) {
            $res[] = [
                'id' => $val->id,
                'name' => $val->name,
                'full_name' => $val->full_name,
                'logo' => $val->logo 
            ];
        }

        return $res;
    }
}
