<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\ParamTypes;
use App\Filters;
use App\PartyParams;
use App\Products;
use JSRender;

class Brands extends Model
{
    protected $table = 'brands';
    protected $fillable = ['id'];
    public $incrementing = false;

    public static function getBrandsCategory($id) {
      $res = [];
      $type_pr = ParamTypes::where('name', 'Производитель')->first();
      $type_pr = $type_pr->id;
      $rows = PartyParams::where(['party_params.param_type_id' => $type_pr, 'party_params.value_id' => $id, 'products.status' => 1])
        ->select('cat1.id as id', 'cat1.image as image' , 'cat1.id_1s as id_1s', 'cat_d1.name as name', 'cat_d1.chpu as chpu', 'cat2.id as idP', 'cat2.image as imageP', 'cat_d2.name as nameP', 'cat1.sort_order as kod_sort', 'cat2.sort_order as kod_sortP', 'party_params.value as value')
        ->join('products','products.id_1s','=','party_params.product_id1s')
        ->join('category as cat1','cat1.id_1s','=','products.parent_id')
        ->join('category_description as cat_d1','cat_d1.id','=','cat1.id')
        ->join('category as cat2','cat2.id_1s','=','cat1.parent_id')
        ->join('category_description as cat_d2','cat_d2.id','=','cat2.id')
        ->groupBy('cat1.id', 'cat1.image', 'cat1.id_1s', 'cat_d1.name', 'cat_d1.chpu', 'cat2.id', 'cat2.image', 'cat_d2.name', 'cat1.sort_order', 'cat2.sort_order', 'party_params.value')
        ->orderBy('cat2.sort_order')
        ->orderBy('cat1.sort_order')
        ->get();
      $flt = Filters::where('name', 'Производитель')->first();

      foreach ($rows as $val) {
        if(!isset($res[$val->idP])) {
          $res[$val->idP] = [
            'imageP' => JSRender::resizeImage($val->imageP,80,80),
            'nameP' => $val->nameP,
            'kod_sort' => $val->kod_sortP,
            'idP' => $val->idP,
            'child' => []
          ];
        }
        $list = PartyParams::getListParamsForCategory($val->id_1s, $type_pr);
        $sh = array_search($val->value, $list);

        $res[$val->idP]['child'][] = [
          'id' => $val->id,
          'image' => JSRender::resizeImage($val->image,35,35),
          'chpu' => '/category/' . $val->chpu . '?f[' . $flt->id_1s . ']=' . $sh,
          'name' => $val->name
        ];
      }
      return $res;
    }

    public static function getBrandByChpu($id) {
        $val = Brands::where('chpu', $id)->first();
        if ($val) {
          $catComp = Brands::getBrandsCategory($val->id);
          return [
            'id' => $val->id,
            'name' => $val->name,
            'full_name' => $val->full_name,
            'chpu' => $val->chpu,
            'image' => JSRender::resizeImage($val->logo,100,40),
            'description' => $val->comment,
            'cite' => $val->cite,
            'brandsCat' => $catComp
          ];
        }
        return [];
    }

    public static function getBrands() {

      $pt = ParamTypes::where('name', 'Производитель')->first();
      $rows = Products::where('products.status', 1)
        ->join('party_params', function ($join) use ($pt) {
            $join->on('party_params.product_id1s', '=', 'products.id_1s')
                 ->where('party_params.param_type_id', '=', $pt->id);
        })
        ->join('brands', 'party_params.value_id', '=', 'brands.id')
        ->select('brands.id as idbrand', 'brands.name as name', 'brands.full_name as full_name', 'brands.chpu as chpu', 'brands.kod_sort as kod_sort', 'brands.logo as logo', 'brands.comment as comment')
        ->orderBy('brands.kod_sort')
        ->distinct()->get();


        //$rows = Brands::where('status', 1)
        //  ->orderBy('kod_sort')->get();
        
        $res = [];
        foreach ($rows as $val) {
            $res[] = [
                'id' => $val->idbrand,
                'name' => $val->name,
                'full_name' => $val->full_name,
                'chpu' => $val->chpu,
                'kod_sort' => $val->kod_sort,
                'image' => JSRender::resizeImage($val->logo,100,40),
                'description' => mb_strimwidth(strip_tags($val->comment),0,300,'...')
            ];
        }

        return $res;
    }
}
