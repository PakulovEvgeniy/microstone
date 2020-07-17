<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;
use JSRender;
use DB;
use App\Orders;
use App\Groups;
use App\Filters;
use App\PartyParams;
use App\PriceParty;
use App\ProductsDescriptions;
use App\ProductPictures;
use App\ParamTypes;
use App\Setting;
use App\DiscountPriceGroup;
use App\Characteristics;
use App\SoldProduct;
use App\Category;
class Products extends Model
{
    protected $table = 'products';
    protected $fillable = ['id_1s'];

    public function products_descriptions()
    {
        return $this->hasOne('App\ProductsDescriptions');
    }

    /*public static function getProductsByChpu($chpu)
    {
    	$cat = Category_description::where('chpu', $chpu)->first();
    	if ($cat) {
    		return Products::getProductsCategory($cat->category->id_1s);
    	}
    	return [];
    }*/
    public static function getProductByChpu($chpu)
    {
    	$cat = Category_description::where('chpu', $chpu)->first();
    	if ($cat) {
    		return $cat->category->id_1s;
    	}
    	return false;
    }
    public static function getId_1sByChpu($chpu)
    {
    	$prod = ProductsDescriptions::where('chpu', $chpu)->first();
    	if ($prod) {
    		return $prod->products->id_1s;
    	}
    	return false;
    }
    
    public static function getPopularProduct() {
        $res = [];
        $type_pr = ParamTypes::where('name', 'Производитель')->first();
        $type_pr = $type_pr->id;


        $row = Products::where(['products.status' => 1])
            ->orderBy('popul', 'DESC')
            ->limit(8)->get();
        foreach ($row as $val) {
            $arr_manuf = PartyParams::getArrayOfParams($val->id_1s, $type_pr);
            $price = PriceParty::getMinMaxPriceForProduct($val->id_1s);

            $res[] = [
                'id' => $val->id,
                'image' => JSRender::resizeImage($val->image,80,80),
                'name' => $val->products_descriptions->name,
                'chpu' => $val->products_descriptions->chpu,
                'manuf' => $arr_manuf,
                'min_price' => $price['min'],
                'max_price' => $price['max']
            ];
        }
        return $res;
    }



    public static function getProductsList($list, $with_params=false)
    {
        $res = [];
        $row = Products::whereIn('products.id', $list)
            ->select('products.id_1s as id_1s', 'products.id as id', 'products_descriptions.chpu as chpu' , 'products_descriptions.name as name', 'sku', 'products.image as image', 'category_description.name as cat_name', 'compare_groups.id as cg_id', 'compare_groups.name as cg_name', 'category.id as cat_id', 'have_charact' , DB::raw('SUM(stock) as stock, MIN(price) as min_price'))
            ->leftJoin('products_descriptions','products.id','=','products_descriptions.products_id')
            ->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')
            ->leftJoin('stock_party', 'products.id_1s','=','stock_party.product_id1s')
            ->leftJoin('category','products.parent_id','=','category.id_1s')
            ->leftJoin('compare_groups','products.compare_group','=','compare_groups.id_1s')
            ->leftJoin('category_description','category.id','=','category_description.category_id')
            ->groupBy('id_1s', 'id', 'name', 'chpu', 'sku', 'image', 'cat_name', 'cg_id', 'cg_name', 'cat_id', 'have_charact')
            ->get();

        //$price_g = $min_price - (10/100*$min_price);

        foreach ($row as $val) {
            $min_price = $val->min_price ? $val->min_price : 0;
            $price_disc = round($min_price,2);//round($min_price*0.9, 2);
            $prod_params=[];
            if ($with_params) {
               $prod_params = PartyParams::getProductParams($val->id_1s); 
            }

            $pict = ProductPictures::getPictures($val->id_1s);
            $pc2=[];
            $pc2[] = '/image/' . $val->image;
            foreach ($pict as $vl) {
                if ($vl['image'] != $val->image) {
                    $pc2[] = '/image/' . $vl['image'];
                }
            }


            $res[] = [
                'id' => $val->id,
                'id_1s' => $val->id_1s,
                'name' => $val->name,
                'chpu' => $val->chpu,
                'sku' => $val->sku,
                'stock' => $val->stock,
                'price' => $min_price,
                'price_with_discount' => $price_disc,
                'percent' => 0,
                'image' => JSRender::resizeImage($val->image,68,55),
                'image_2' => JSRender::resizeImage($val->image,80,80),
                'cat_id' => $val->cat_id,
                'cat_name' => $val->cat_name,
                'cg_id' => $val->cg_id,
                'cg_name' => $val->cg_name,
                'product_params' => $prod_params,
                'images' => $pc2,
                'have_charact' => $val->have_charact,
                'characts' => $val->have_charact ? Characteristics::getCharacteristics($val->id_1s) : [],
                'characts_price' => $val->have_charact ? PriceParty::getPriceForProductCharact($val->id_1s) : []
            ];
        }

        return $res;
    }

    public static function getProduct($id_1s)
    {
        $prod = Products::where('id_1s', $id_1s)->first();

        $pict = ProductPictures::getPictures($id_1s);
        $pc = [];
        $pc1 = [];
        $pc[] = JSRender::resizeImage($prod->image,325,240);
        $pc1[] = JSRender::resizeImage($prod->image,68,55);
        $pc2[] = '/image/' . $prod->image;
        foreach ($pict as $val) {
            if ($val['image'] != $prod->image) {
                $pc[] = JSRender::resizeImage($val['image'],325,240);
                $pc1[] = JSRender::resizeImage($val['image'],68,55);
                $pc2[] = '/image/' . $val['image'];
            }
        }

                
    	if ($prod) {
            $arr_manuf = PartyParams::getManufacturesByProduct($id_1s);
            $cat = Category::where('id_1s', $prod->parent_id)->first();
            $chpu="";
            if ($cat) {
                $chpu = '/category/' . $cat->category_description->chpu . '?';
            }
            
            $disc = DiscountPriceGroup::getDiscounts($prod->price_group);
            $prod_params = PartyParams::getProductParams($id_1s);

            foreach ($prod_params as $key => $val) {
                $filt = Filters::where('param_type_id', $val['param_type_id'])->first();
                if (!$filt) {
                    continue;
                }
                $sh = '';
                if ($filt->filter_type == 'Число') {
                    $sh = $val['value'] . '-' . $val['value'];
                } else {
                    $list = PartyParams::getListParamsForCategory($prod->parent_id, $val['param_type_id']);
                    $sh = array_search($val['value'], $list);
                }
                $prod_params[$key]['filtr_chpu'] = $chpu . 'f[' . $filt->id . ']=' . $sh;
            }
            $charact=[];
            if ($prod->have_charact) {
                $charact = Characteristics::getCharacteristics($prod->id_1s);
                $price  = PriceParty::getPriceForProductCharact($id_1s);
            } else {
               $price  = PriceParty::getMinMaxPriceForProduct($id_1s); 
            }

            $res=[
                'id' => $prod->id,
                'id_1s' => $prod->id_1s,
                'name' => $prod->products_descriptions->name,
                'parent_id' => $prod->parent_id,
                'chpu' => $prod->products_descriptions->chpu,
                'description' => $prod->products_descriptions->description,
                'sku' => $prod->sku,
                'images' => $pc,
                'images2' => $pc1,
                'bigImages' => $pc2,
                'manuf' => $arr_manuf,
                'price' => $price,
                'discount_group' => $disc,
                'params' => $prod_params,
                'have_charact' => $prod->have_charact,
                'characts' => $charact
            ];
            $prod->popul = $prod->popul+1;
            $prod->save();
    		return $res;
    	}
    	return false;
    }

    public static function getGrpDataOfProductsCategory($val, $id_1s) {
        
            $it = [];
            if ($val->id_1s == 1) {
                $minmax = PriceParty::getMinMaxPriceForCategory($id_1s);
                //dd($minmax);
                $it['min'] = !$minmax['min'] ? 0 : $minmax['min'];
                $it['max'] = !$minmax['max'] ? 0 : $minmax['max'];
                if ($it['max'] == 0) {
                    $it['max'] = 1000;
                }
                $it['minValue'] = '';
                $it['maxValue'] = '';
            } else {
                if ($val->filter_type == 'Число' && $val->param_type_id) {
                    $minmax = PartyParams::getMinMaxValueForCategory($id_1s, $val->param_type_id);
                    $it['min'] = !$minmax['min'] ? 0 : $minmax['min'];
                    $it['max'] = !$minmax['max'] ? 0 : $minmax['max'];
                    if ($it['max'] == 0) {
                        $it['max'] = 1000;
                    }
                    $it['minValue'] = '';
                    $it['maxValue'] = '';
                }
                if ($val->filter_type == 'Строка' && $val->param_type_id) {
                    $items = PartyParams::getListParamsForCategory($id_1s, $val->param_type_id);
                    $it['items'] = $items;
                    $it['fChecked'] = [];
                }
            }
        
            return $it;
    }

    public static function searchProducts($qu) 
    {
        if (isset($qu['perPage'])) {
            $pgCount = intval($qu['perPage']);
        }
        if (!$pgCount) {
            $pgCount = 10;
        }
        $page = 0;
        if (isset($qu['page'])) {
            $page = intval($qu['page']);
        }
        if (!$page) {
            $page = 0;
        }
        

        $prd = Products::where(['products.status' => 1])
            ->select('products.id as id')
            ->leftJoin('products_descriptions','products.id','=','products_descriptions.products_id')
            ->where('name', 'like' , '%' . $qu['value'] . '%');

        $qty = $prd->get()->count();

        $prd = $prd->orderBy('id')->offset(($page)*$pgCount)->limit($pgCount)->get(); 

        $dat = [];

        foreach ($prd as $val) {
            $dat[] = $val['id'];
        }
        return ['totalQty' => $qty, 'items' => self::getProductsList($dat)];
    }

    public static function getProductsCategoryPage($id_1s, $filtr) {
       
        $param_ord = Orders::getParamsOrders($id_1s);
        $fld = [];
        $fld_type = [];

        $group_def = Groups::getDefaultGroupId($id_1s);
        $group = $group_def;
        if (isset($filtr['group'])) {
            $group = intval($filtr['group']);
            if (!$group) {
                $group = $group_def;
            }
        }

        $rowSet = Setting::where('setting_id', '=', 'prod_count')->first();
        $pgCount = intval($rowSet->name);

        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('products_descriptions','products.id','=','products_descriptions.products_id')
			->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')
			->leftJoin('stock_party', 'products.id_1s','=','stock_party.product_id1s')
            ->leftJoin('sold_product','products.id_1s','=','sold_product.product_id1s');
            
        if (isset($filtr['q']) && $filtr['q']) {
            $prd = $prd->where('name', 'like' , '%' . $filtr['q'] . '%');
        }

        if (isset($filtr['f']) && is_array($filtr['f'])) {
          foreach ($filtr['f'] as $key => $value) {
            if ($key==1) {
              $arrProds = PriceParty::getProductsByFiltr($id_1s, $value);  
            } else {
              $arrProds = PartyParams::getProductsByFiltr($id_1s, $key, $value);
            }
            if ($arrProds!==false) {
              $prd = $prd->whereIn('id_1s', $arrProds);
            }
          } 
        }

        if (count($param_ord)) {
            foreach ($param_ord as $val) {
                $sort_field = 'f' . $val['param_type_id'];
                if (in_array($sort_field, $fld)) {
                    continue;
                }
                $fld[] = $sort_field;
                $fld_type[] = $val['sort_type'];
                $alias = 'party_params' . $sort_field;
                $prd = $prd->leftJoin('party_params as ' . $alias, function ($join) use ($val, $alias)
                {
                    $join->on('products.id_1s','=',$alias . '.product_id1s')
                    ->where($alias . '.param_type_id','=',$val['param_type_id']);
                });
            }
        }
        $prd = $prd->select('id_1s', 'products.id as id', 'description', 'chpu' ,'parent_id', 'name', 'sku', 'image', 'popul', 'have_charact', DB::raw('MAX(price) as max_price, MIN(price) as min_price, 
					SUM(stock) as stock, MAX(sold) as sold'));
        if (count($fld)) {
            for ($i=0; $i < count($fld); $i++) { 
                $val = $fld[$i];
                $alias = 'party_params' . $val;
                if ($fld_type[$i]=='Строка') {
                    $str = $alias . '.value) as ';   
                } else {
                    $str = 'CAST(' . $alias . '.value AS DECIMAL(15,5))) as ';
                }
                $prd = $prd->addSelect(DB::raw('MAX(' . $str . $val));
            }
        }
        $grp = Groups::find($group);
        if ($grp) {
            if ($grp->name == 'По наличию') {
                $prd = $prd->addSelect(DB::raw("MIN(CASE WHEN stock>0 THEN 'В наличии' ELSE 'Под заказ' END) as grp"));
            } elseif ($grp->name != 'Без группировки') {
                if ($grp->param_type_id) {
                    $prd = $prd->leftJoin('party_params as party_paramsGroup', function ($join) use ($grp)
                    {
                        $join->on('products.id_1s','=','party_paramsGroup.product_id1s')
                        ->where('party_paramsGroup.param_type_id','=',$grp->param_type_id);
                    })->addSelect(DB::raw("CASE WHEN party_paramsGroup.value is null THEN 'я' ELSE party_paramsGroup.value END as grp"))
                    ->groupBy('party_paramsGroup.value');
                }
            }    
        }
        
        
        $prd = $prd->groupBy('id_1s', 'id', 'name', 'description', 'chpu', 'parent_id', 'sku', 'image', 'popul', 'have_charact');

        $order_def = Orders::getDefaultOrderId($id_1s);
        $order = $order_def;
        if (isset($filtr['order'])) {
            $order = intval($filtr['order']);
            if (!$order) {
                $order = $order_def;
            }
        }

        if ($grp && ($grp->name == 'По наличию' || $grp->param_type_id)) {
            $prd = $prd->orderBy('grp'); 
        }

        $ord = Orders::find($order);
        if ($ord) {
            $sort_field = '';
            if ($ord->param_type_id) {
                $sort_field = 'f' . $ord->param_type_id;
            } else {
                if ($ord->name == 'По возрастанию цены' || $ord->name == 'По убыванию цены') {
                    $sort_field = 'min_price';
                } elseif ($ord->name == 'По наименованию') {
                    $sort_field = 'name';
                } elseif ($ord->name == 'По продажам') {
                    $sort_field = 'sold';
                } elseif ($ord->name == 'По популярности') {
                    $sort_field = 'popul';
                }
            }
            if ($sort_field) {
                $prd = $prd->orderBy($sort_field, $ord->sort_ord);   
            }
            //} else {
            //    $prd = $prd->orderByRaw('CAST(' . $ord->sort_field . ' AS DECIMAL(15,5)) ' . $ord->sort_ord);
            //}
        }

        $prd = $prd->orderBy('id');

        $stock = 1;
        if (isset($filtr['stock'])) {
            $stock = intval($filtr['stock']);
            if (!$stock) {
                $stock = 1;
            }
        }
        if ($stock == 2) {
            $prd = $prd->havingRaw('SUM(stock) > 0'); 
        }
        if ($stock == 3) {
            $prd = $prd->havingRaw('SUM(stock) is null or SUM(stock) = 0'); 
        }

        $qty = $prd->get()->count();

        
        $page = 1;
        if (isset($filtr['page'])) {
            $page = intval($filtr['page']);
            if (!$page || $page>ceil($qty/$pgCount)) {
                $page = 1;
            }
        }
        $prd = $prd->offset(($page-1)*$pgCount)->limit($pgCount)->get(); 

    	$dat = [];

    	foreach ($prd as $val) {

    		$d = [
				'id' => $val->id,
				'parent_id' => $val->parent_id,
				'id_1s' => $val->id_1s,
				'sku' => $val->sku,
				'image' => JSRender::resizeImage($val->image,200,200),
				'name' => $val->name,
				'description' => $val->description,
				'small_desc' => mb_strimwidth(strip_tags($val->description),0,100,'...'),
				'chpu' => $val->chpu,
				'max_price' => $val->max_price,
				'min_price' => $val->min_price,
				'stock' => $val->stock,
				'sold' => $val->sold ? $val->sold : 0,
                'have_charact' => $val->have_charact,
                'characts' => $val->have_charact ? Characteristics::getCharacteristics($val->id_1s) : []
            ];
            if ($grp && ($grp->name == 'По наличию' || $grp->param_type_id)) {
                $d['group'] = $val->grp=='я' ? 'Неопределено' : $val->grp; 
            }
            $dat[] = $d;
        }
        return ['totalQty' => $qty, 'items' => $dat];
    }

    /*public static function getProductsCategory($id_1s)
    {
		$param_ord = Orders::getParamsOrders($id_1s);
        $fld = [];


        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
			->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')
			->leftJoin('stock_party', 'products.id_1s','=','stock_party.product_id1s')
			->leftJoin('sold_product','products.id_1s','=','sold_product.product_id1s');
        if (count($param_ord)) {
            foreach ($param_ord as $val) {
                $sort_field = 'f' . $val['param_type_id'];
                if (in_array($sort_field, $fld)) {
                    continue;
                }
                $fld[] = $sort_field;
                $alias = 'party_params' . $sort_field;
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
    }*/
}
