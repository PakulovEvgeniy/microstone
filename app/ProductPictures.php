<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPictures extends Model
{
    protected $table = 'product_pictures';
    protected $fillable = ['product_id1s', 'party_id1s', 'image'];
    public $timestamps = false;

    public static function getPictures($id_1s) {
        $row = ProductPictures::where('product_id1s', '=', $id_1s)->get();
        $res = [];
        foreach ($row as $val) {
            $res[] = [
                'image' => $val->image,
                'party_id1s' => $val->party_id1s
            ];
        }
        return $res;
    }
}
