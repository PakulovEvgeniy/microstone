<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;
use App\Category;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = ['id_1s'];
    public $timestamps = false;

    public function orders_group()
    {
        return $this->hasMany('App\OrdersGroups');
    }

    public static function getOrders($id_1s)
    {
        $rows = Orders::where('status', '=', '1')
            ->has('orders_group', '=', 0)
            ->orHas('orders_group', '>', 0)
            ->whereHas('orders_group', function ($q) use ($id_1s)
            {
                $q->where('category_id', '=', $id_1s);
            })
            ->orderBy('sort_order')
            ->get();
        
        $res = [];
        foreach ($rows as $val) {
            $res[] = [
                'id' => $val->id,
                'name' => $val->name,
                'sort_ord' => $val->sort_ord,
                'sort_type' => $val->sort_type
            ];
        }
        $cat = Category::where('id_1s', $id_1s)->first();
        if ($cat) {
            $cat->popul = $cat->popul + 1;
            $cat->save();
        }

    	return $res;
    }
    public static function getDefaultOrderId($id_1s) {
        $row = Orders::where('status', '=', '1')
            ->has('orders_group', '=', 0)
            ->orHas('orders_group', '>', 0)
            ->whereHas('orders_group', function ($q) use ($id_1s)
            {
                $q->where('category_id', '=', $id_1s);
            })
            ->orderBy('sort_order')
            ->first();
        return $row->id;
    }

    public static function getParamsOrders($id_1s)
    {
        $rows = Orders::where([['param_type_id', '<>', ''], ['status', '=', '1']])
            ->has('orders_group', '=', 0)
            ->orHas('orders_group', '>', 0)
            ->whereHas('orders_group', function ($q) use ($id_1s)
            {
                $q->where('category_id', '=', $id_1s);
            })
            ->orderBy('sort_order')
            ->get();
        //dd($rows);
        $res = [];
        foreach ($rows as $val) {
            $res[] = [
                'id' => $val->id,
                'name' => $val->name,
                'sort_ord' => $val->sort_ord,
                'sort_type' => $val->sort_type,
                'param_type_id' => $val->param_type_id
            ];
        }
        return $res;
    }
    public static function getOrdersByChpu($chpu)
    {
        $cat = Category_description::where('chpu', $chpu)->first();
        if ($cat) {
            return [
                'name' => 'order',
				'date' => '',
				'caption' => 'Сортировать:',
				'items' => Orders::getOrders($cat->category->id_1s)
            ];
        }
        return false;
    }
}
