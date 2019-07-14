<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Products;

class Filters extends Model
{
    protected $table = 'filters';
    protected $fillable = ['id_1s'];
    public $timestamps = false;

    public function filters_groups()
    {
        return $this->hasMany('App\FiltersGroups');
    }

    public static function getFilters($id_1s)
    {
        $rows = Filters::where('status', '=', 1)->has('filters_groups', '=', 0)
            ->orHas('filters_groups', '>', 0)
            ->whereHas('filters_groups', function ($q) use ($id_1s)
            {
                $q->where('category_id', '=', $id_1s);
            })
            ->orderBy('sort_order')
            ->get();
        //dd($rows);
        $res = [];
        foreach ($rows as $val) {
            $it = Products::getGrpDataOfProductsCategory($val, $id_1s);
            $res[] = [
                'id' => $val->id,
                'id_1s' => $val->id_1s,
                'name' => $val->name,
                'filter_field' => $val->filter_field,
                'param_type_id' => $val->param_type_id,
                'filter_type' => $val->filter_type,
                'grp_data' => $it
            ];
            
        }
    	return $res;
    }
    public static function getFiltersByChpu($chpu)
    {
        $cat = Category_description::where('chpu', $chpu)->first();
        if ($cat) {
            return Filters::getFilters($cat->category->id_1s);
        }
        return false;
    }
}
