<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;

class Groups extends Model
{
    protected $table = 'groups';
    protected $fillable = ['id_1s'];
    public $timestamps = false;

    public function groups_groups()
    {
        return $this->hasMany('App\GroupsGroups');
    }

    public static function getDefaultGroupId($id_1s) {
        $row = Groups::where('status', '=', '1')
            ->has('groups_groups', '=', 0)
            ->orHas('groups_groups', '>', 0)
            ->whereHas('groups_groups', function ($q) use ($id_1s)
            {
                $q->where('category_id', '=', $id_1s);
            })
            ->orderBy('sort_order')
            ->first();
        return $row->id;
    }
    public static function getGroups($id_1s)
    {
        $rows = Groups::where('status', '=', '1')
            ->has('groups_groups', '=', 0)
            ->orHas('groups_groups', '>', 0)
            ->whereHas('groups_groups', function ($q) use ($id_1s)
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
                'param_type_id' => $val->param_type_id
            ];
        }
    	return $res;
    }
    public static function getGroupsByChpu($chpu)
    {
        $cat = Category_description::where('chpu', $chpu)->first();
        if ($cat) {
            return [
                'name' => 'group',
				'date' => '',
				'caption' => 'Группировать:',
				'items' => Groups::getGroups($cat->category->id_1s)
            ];
        }
        return false;
    }
}
