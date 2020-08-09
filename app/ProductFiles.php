<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFiles extends Model
{
    protected $table = 'product_files';
    protected $fillable = ['product_id'];
    public $timestamps = false;

    public static function getFiles($id) {
        $row = ProductFiles::where('product_id', '=', $id)->get();
        $res = [];
        foreach ($row as $val) {
            $res[] = [
            	'id' => $val->id,
                'name' => $val->name,
                'file' => '/image/files/' . $val->filename
            ];
        }
        return $res;
    }
}
