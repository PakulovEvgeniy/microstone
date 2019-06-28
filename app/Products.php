<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;
use JSRender;
use DB;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = ['id_1s'];

    public function products_descriptions()
    {
        return $this->hasOne('App\ProductsDescriptions');
    }

    public static function getProductsByChpu($chpu)
    {
    	$cat = Category_description::where('chpu', $chpu)->first();
    	if ($cat) {
    		return Products::getProductsCategory($cat->category->id_1s);
    	}
    	return [];
    }

    public static function getProductsCategory($id_1s)
    {
    	$prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')->leftJoin('stock_party', 'products.id_1s','=','stock_party.product_id1s')->select('id_1s', 'id', 'parent_id', 'sku', 'image', DB::raw('MAX(price) as max_price, MIN(price) as min_price, SUM(stock) as stock'))->groupBy('id_1s', 'id', 'parent_id', 'sku', 'image')->with('products_descriptions')->get(); 

    	$dat = [];

    	foreach ($prd as $val) {
    		$dat[] = [
				'id' => $val->id,
				'parent_id' => $val->parent_id,
				'id_1s' => $val->id_1s,
				'sku' => $val->sku,
				'image' => JSRender::resizeImage($val->image,200,200),
				'name' => $val->products_descriptions->name,
				'description' => $val->products_descriptions->description,
				'small_desc' => mb_strimwidth(strip_tags($val->products_descriptions->description),0,150,'...'),
				'chpu' => $val->products_descriptions->chpu,
				'max_price' => $val->max_price,
				'min_price' => $val->min_price,
				'stock' => $val->stock
			];
    	}
    	return $dat;
    }
}
