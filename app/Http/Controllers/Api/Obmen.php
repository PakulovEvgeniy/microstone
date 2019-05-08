<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Category_description;

class Obmen extends Controller
{
    protected function translit($s) {
	  $s = (string) $s; // преобразуем в строковое значение
	  $s = strip_tags($s); // убираем HTML-теги
	  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
	  //$s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
	  $s = trim($s); // убираем пробелы в начале и конце строки
	  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
	  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
	  $s = preg_replace("/[^0-9a-z-_ ]/i", "_", $s); // очищаем строку от недопустимых символов
	  $s = str_replace(" ", "_", $s); // заменяем пробелы знаком минус
	  return $s; // возвращаем результат
	}

    public function index(Request $request) {
    	$par = $request->all();
    	if (!isset($par['param'])) {
    		return 'OK';
    	}
    	$pr = $par['param'];
    	if ($pr == 'group') {
    		$id_1s = $par['gr_id'];
    		$rodit = $par['gr_rodit'];
    		if (!$rodit) {
    			$rodit = '';
    		}
    		$name = $par['gr_name'];
    		$kodsort = $par['gr_kodsort'];
    		$chpu = $par['gr_chpu'];
    		$active = $par['gr_active'];
    		if (!$active) {
    			$active = 0;
    		}
    		$pict = $par['gr_pict'];
    		$meta_title = $par['gr_meta_title'];
    		if (!$meta_title) {
    			$meta_title = $name;
    		}
    		$meta_description = $par['gr_meta_description'];
    		if (!$meta_description) {
    			$meta_description = $name;
    		}
    		$meta_keyword = $par['gr_meta_keyword'];
    		if (!$meta_keyword) {
    			$meta_keyword = $name;
    		}
    		$opis = $par['gr_opis'];
    		if (!$opis) {
    			$opis = '';
    		}
    		if (!$kodsort) {
    			$kodsort = 0;
    		}
    		if (!$pict) {
    			$pict = 'no_image.png';
    		}
    		if (!$chpu) {
    			$chpu = $this->translit($name);
    		}
    		$grp = Category::firstOrNew(['id_1s' => $id_1s]);
    		$grp->parent_id = $rodit;
    		$grp->id_1s = $id_1s;
    		$grp->image = 'catalog/' . $pict;
    		$grp->status = $active;
    		$grp->sort_order = $kodsort;
    		$grp->parent_id = $rodit;
    		$grp->save();

    		$grp_desc = Category_description::firstOrNew(['category_id' => $grp->id]);

    		$grp_desc->name = $name;
    		$grp_desc->category_id = $grp->id;
    		$grp_desc->description = $opis;
    		$grp_desc->chpu = $chpu;
    		$grp_desc->meta_title = $meta_title;
    		$grp_desc->meta_description = $meta_description;
    		$grp_desc->meta_keyword = $meta_keyword;
    		$grp_desc->save();

    		return 'OK';
    	}

    	if ($pr == 'picture') {
    		$uploadFile = $_FILES['datafile'];
    		$tmp_name = $uploadFile['tmp_name'];
    		$data_filename = "image/catalog/" . $uploadFile['name'];
    		$data = file_get_contents($tmp_name);
    		$data = base64_decode($data);
    		if (!empty($data) && ($fp = fopen($data_filename, 'wb'))) {
    			fwrite($fp, $data);
    			fclose($fp);
    			return 'OK';
    		} else {
    			return 'Error save file.';
    		}
    	}
    	return 'OK';
    }
}
