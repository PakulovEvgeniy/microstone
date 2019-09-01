<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category_description;
use JSRender;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = ['id_1s'];

    public function category_description()
    {
        return $this->hasOne('App\Category_description');
    }

    public static function getCategoryByChpu($chpu)
    {
    	$cat = Category_description::where('chpu', $chpu)->first();
    	if ($cat) {
    		return [
    			'id' => $cat->category_id,
    			'name' => $cat->name,
    			'id_1s' => $cat->category->id_1s
    		];
    	}
    	return false;
    }

    public static function getCountChild($id_1s)
    {
    	$count = Category::where('parent_id', $id_1s)->count();
    	return $count;
    }

    public static function getBanners()
    {
        $dat = [];

        $dat[] = [
            'id' => 1,
            'image' => JSRender::resizeImage('catalog/banners/banner_00.jpg',908,266)
        ];

        $dat[] = [
            'id' => 2,
            'image' => JSRender::resizeImage('catalog/banners/banner_01.jpg',908,266)
        ];

        $dat[] = [
            'id' => 3,
            'image' => JSRender::resizeImage('catalog/banners/banner_02.jpg',908,266)
        ];

        $dat[] = [
            'id' => 4,
            'image' => JSRender::resizeImage('catalog/banners/banner_03.jpg',908,266)
        ];

        $dat[] = [
            'id' => 5,
            'image' => JSRender::resizeImage('catalog/banners/banner_04.jpg',908,266)
        ];

        return $dat;
    }

    public static function getPopularCategory() {
        $res = [];
        $row = Category::where(['status' => 1])
            ->orderBy('popul', 'DESC')
            ->limit(8)->get();
        foreach ($row as $val) {
            $res[] = [
                'id' => $val->id,
                'image' => JSRender::resizeImage($val->image,80,80),
                'name' => $val->category_description->name,
                'chpu' => $val->category_description->chpu
            ];
        }
        return $res;
    }

    public static function getCatalog($parent)
	{
		$dat = [];
		$rows = Category::where(['status' => 1, 'parent_id' => $parent])->
			orderBy('sort_order', 'asc')->get();

		foreach ($rows as $val) {
			//$child = [];
			$child = Category::getCatalog($val->id_1s);
			$dat[] = [
				'id' => $val->id,
				'parent_id' => $val->parent_id,
				'id_1s' => $val->id_1s,
				'image' => JSRender::resizeImage($val->image,22,22),
				'image2' => JSRender::resizeImage($val->image,80,80),
				'sort_order' => $val->sort_order,
				'name' => $val->category_description->name,
				'description' => $val->category_description->description,
				'chpu' => $val->category_description->chpu,
				'childrens' => $child
			];
		}
		return $dat;
	}
}
