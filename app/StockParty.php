<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockParty extends Model
{
    protected $table = 'stock_party';
    protected $fillable = ['product_id1s', 'party_id1s'];
    public $timestamps = false;

    public static function getStock($id_1s, $id) {
        $row = StockParty::where('product_id1s', '=', $id_1s)->get();
        $res = [];
        foreach ($row as $val) {
            $res[] = [
                'id' => $id,
                'characteristic' => $val->party_id1s,
                'stock' => $val->stock
            ];
        }
        return $res;
    }
}
