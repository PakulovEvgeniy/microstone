<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;

class AppController extends Controller
{
     
	protected function getCatalog($parent)
	{
		$dat = [];
		$rows = Category::where(['status' => 1, 'parent_id' => $parent])->
			orderBy('sort_order', 'asc')->get();

		foreach ($rows as $val) {
			//$child = [];
			$child = $this->getCatalog($val->id_1s);
			$dat[] = [
				'id' => $val->id,
				'parent_id' => $val->parent_id,
				'image' => JSRender::resizeImage($val->image,22,22),
				'sort_order' => $val->sort_order,
				'name' => $val->category_description->name,
				'description' => $val->category_description->description,
				'chpu' => $val->category_description->chpu,
				'meta_title' => $val->category_description->meta_title,
				'meta_description' => $val->category_description->meta_description,
				'meta_keyword' => $val->category_description->meta_keyword,
				'childrens' => $child
			];
		}
		return $dat;
	}

     public function get(Request $request) {
     	//phpinfo();
     	//return;
     	$data = ['date' => '', 'items' => $this->getCatalog('')];

        $ssr = JSRender::render($request->path(), ['catalog' => $data]);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr]);
    }
}

