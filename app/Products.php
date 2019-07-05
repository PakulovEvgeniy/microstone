<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;
use JSRender;
use DB;
use App\Orders;
use App\PartyParams;

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
		$param_ord = Orders::getParamsOrders($id_1s);
        $fld = [];


        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
			->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')
			->leftJoin('stock_party', 'products.id_1s','=','stock_party.product_id1s')
			->leftJoin('sold_product','products.id_1s','=','sold_product.product_id1s');
        if (count($param_ord)) {
            foreach ($param_ord as $val) {
                if (in_array($val['sort_field'], $fld)) {
                    continue;
                }
                $fld[] = $val['sort_field'];
                $alias = 'party_params' . $val['sort_field'];
                $prd = $prd->leftJoin('party_params as ' . $alias, function ($join) use ($val, $alias)
                {
                    $join->on('products.id_1s','=',$alias . '.product_id1s')
                    ->where($alias . '.param_type_id','=',$val['param_type_id']);
                });
            }
        }
        $prd = $prd->select('id_1s', 'id', 'parent_id', 'sku', 'image', DB::raw('MAX(price) as max_price, MIN(price) as min_price, 
					SUM(stock) as stock, SUM(sold) as sold'));
        if (count($fld)) {
            foreach ($fld as $val) {
                $alias = 'party_params' . $val;
                $prd = $prd->addSelect(DB::raw('MAX(' . $alias . '.value) as ' . $val));
            }
        }

        $prd = $prd->groupBy('id_1s', 'id', 'parent_id', 'sku', 'image')->with('products_descriptions')->get(); 

    	$dat = [];

    	foreach ($prd as $val) {
    		$d = [
				'id' => $val->id,
				'parent_id' => $val->parent_id,
				'id_1s' => $val->id_1s,
				'sku' => $val->sku,
				'image' => JSRender::resizeImage($val->image,200,200),
				'name' => $val->products_descriptions->name,
				'description' => $val->products_descriptions->description,
				'small_desc' => mb_strimwidth(strip_tags($val->products_descriptions->description),0,100,'...'),
				'chpu' => $val->products_descriptions->chpu,
				'max_price' => $val->max_price,
				'min_price' => $val->min_price,
				'stock' => $val->stock,
				'sold' => $val->sold,
                'params' => PartyParams::getProductParams($val->id_1s)
			];
            if (count($fld)) {
               foreach ($fld as $v) {
                $d[$v] = $val[$v];
               } 
            }
            $dat[] = $d;
    	}
        return $dat;
    }
}
