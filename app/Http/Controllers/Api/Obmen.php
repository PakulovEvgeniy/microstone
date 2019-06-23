<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Orders;
use App\OrdersGroups;
use App\Groups;
use App\GroupsGroups;
use App\Category_description;
use App\Products;
use App\ProductsDescriptions;
use App\PriceParty;

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
		
		if ($pr == 'price_party') {
			$id_prod = $par['pp_id'];
			$id_party = $par['pp_party'];
			$p_type = $par['pp_type'];
			$p_act = $par['pp_act'];
			$p_minqty = $par['pp_minqty'];
			$p_price = $par['pp_price'];
			if ($p_act == 'ПрайсУдалить') {
				PriceParty::where(['product_id1s' => $id_prod, 'party_id1s' => $id_party, 'min_qty' => $p_minqty])->delete();
			} else {
				$arr = PriceParty::where(['product_id1s' => $id_prod, 'party_id1s' => $id_party, 'min_qty' => $p_minqty])->first();
				if ($arr) {
					PriceParty::where(['product_id1s' => $id_prod, 'party_id1s' => $id_party, 'min_qty' => $p_minqty])
					->update(['price' => $p_price]);
				} else {
					$pp = new PriceParty;
					$pp->product_id1s = $id_prod;
					$pp->party_id1s = $id_party;
					$pp->min_qty = $p_minqty;
					$pp->party_type = $p_type;
					$pp->price = $p_price;
					$pp->save();
				}
			}
			return 'OK';
		}

		if ($pr == 'order') {
			$id = $par['ord_id'];
			$name = $par['ord_name'];
			$kod_sort = $par['ord_kod_sort'];
			$status = $par['ord_status'];
			$ord = Orders::firstOrNew(['id_1s' => $id]);
			$ord->id_1s = $id;
			$ord->name = $name;
			$ord->sort_order = $kod_sort;
			$ord->status = $status;
			$ord->save();

			OrdersGroups::where('orders_id', $ord->id)->delete();

			if (isset($par['ord_group'])) {

				foreach ($par['ord_group'] as $value) {
					$grp = new OrdersGroups;
					$grp->orders_id = $ord->id;
					$grp->category_id = $value;
					$grp->save();
				}
			}

			return 'OK';
		}

		if ($pr == 'groups') {
			$id = $par['grp_id'];
			$name = $par['grp_name'];
			$kod_sort = $par['grp_kod_sort'];
			$status = $par['grp_status'];
			$grp = Groups::firstOrNew(['id_1s' => $id]);
			$grp->id_1s = $id;
			$grp->name = $name;
			$grp->sort_order = $kod_sort;
			$grp->status = $status;
			$grp->save();

			GroupsGroups::where('groups_id', $grp->id)->delete();

			if (isset($par['grp_group'])) {

				foreach ($par['grp_group'] as $value) {
					$grp_grp = new GroupsGroups;
					$grp_grp->groups_id = $grp->id;
					$grp_grp->category_id = $value;
					$grp_grp->save();
				}
			}

			return 'OK';
		}

        if ($pr == 'product') {
            $id_1s = $par['pr_id'];
            $rodit = $par['pr_rodit'];
            if (!$rodit) {
                $rodit = '';
            }
            $name = $par['pr_fullname'];
            $sku = $par['pr_kod'];
            $active = $par['pr_active'];
            if (!$active) {
                $active = 0;
            }
            $kod_sort = 0;
            $pict = '';
            if (isset($par['pr_pict'])) {
                $pict = $par['pr_pict'][0];
            }
            if (!$pict) {
                $pict = 'no_image.png';
            }
            $opis = $par['pr_opis'];
            if (!$opis) {
                $opis = $name;
            }
            $chpu = $par['pr_chpu'];
            if (!$chpu) {
                $chpu = $this->translit($name);
            }
            $meta_title = $par['pr_meta_title'];
            if (!$meta_title) {
                $meta_title = $name;
            }
            $meta_description = $par['pr_meta_description'];
            if (!$meta_description) {
                $meta_description = $name;
            }
            $meta_keyword = $par['pr_meta_keyword'];
            if (!$meta_keyword) {
                $meta_keyword = $name;
            }
            $meta_weight = $par['pr_meta_weight'];
            if (!$meta_weight) {
                $meta_weight = 0;
            }



            $grp = Products::firstOrNew(['id_1s' => $id_1s]);
            $grp->parent_id = $rodit;
            $grp->id_1s = $id_1s;
            $grp->image = 'catalog/' . $pict;
            $grp->status = $active;
            $grp->sort_order = $kod_sort;
            $grp->parent_id = $rodit;
            $grp->sku = $sku;
            $grp->save();

            $grp_desc = ProductsDescriptions::firstOrNew(['products_id' => $grp->id]);

            $grp_desc->name = $name;
            $grp_desc->products_id = $grp->id;
            $grp_desc->description = $opis;
            $grp_desc->chpu = $chpu;
            $grp_desc->meta_title = $meta_title;
            $grp_desc->meta_description = $meta_description;
            $grp_desc->meta_keyword = $meta_keyword;
            $grp_desc->meta_weight = $meta_weight;
            
            $grp_desc->save();

            return 'OK';
        }

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
