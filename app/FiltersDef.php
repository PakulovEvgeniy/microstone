<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FiltersDefParams;

class FiltersDef extends Model
{
    protected $table = 'filters_def';
    protected $fillable = ['id_1s'];
    public $timestamps = false;

    public function filters_def_groups()
    {
        return $this->hasMany('App\FiltersDefGroups');
    }

    public function filters_def_params()
    {
        return $this->hasMany('App\FiltersDefParams');
    }

    public static function getFiltersDef($id_1s)
    {
        $rows = FiltersDef::where('status', '=', 1)->has('filters_def_groups', '=', 0)
            ->orHas('filters_def_groups', '>', 0)
            ->whereHas('filters_def_groups', function ($q) use ($id_1s)
            {
                $q->where('category_id', '=', $id_1s);
            })
            ->orderBy('sort_order')
            ->get();
        //dd($rows);
        $res = [];
        foreach ($rows as $val) {
            $it = FiltersDefParams::getParamsForFiltersDef($val->id);
            $res[] = [
                'id' => $val->id,
                'id_1s' => $val->id_1s,
                'name' => $val->name,
                'params' => $it
            ];
            
        }
        return $res;
    }
    public static function getFiltersDefByChpu($chpu)
    {
        $cat = Category_description::where('chpu', $chpu)->first();
        if ($cat) {
            return FiltersDef::getFiltersDef($cat->category->id_1s);
        }
        return false;
    }
}
