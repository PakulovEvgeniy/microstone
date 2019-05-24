<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class Products extends Controller
{
    public function index(Request $request) {
    	$param = $request->route('id');
    	$stat = 'ER';
    	$data = [];
    	if ($param == 'category') {
    		$stat = 'OK';
    		$data = Category::getCatalog('');
    	}
    	return ['status' => $stat, 'data' => $data];
    }
}
