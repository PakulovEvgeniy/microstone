<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;
use JSRender;

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
    	$prods = Products::where(['status' => 1, 'parent_id' => $id_1s])->get();
    	$dat = [];

    	foreach ($prods as $val) {
    		$dat[] = [
				'id' => $val->id,
				'parent_id' => $val->parent_id,
				'id_1s' => $val->id_1s,
				'sku' => $val->sku,
				'image' => JSRender::resizeImage($val->image,200,200),
				'name' => $val->products_descriptions->name,
				'description' => $val->products_descriptions->description,
				'small_desc' => mb_strimwidth(strip_tags($val->products_descriptions->description),0,150,'...'),
				'chpu' => $val->products_descriptions->chpu
			];
    	}
    	return $dat;
    }
}
