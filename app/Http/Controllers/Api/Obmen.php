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
use App\StockParty;
use App\SoldProduct;
use App\ParamTypes;
use App\PartyParams;
use App\Brands;
use App\Cases;
use App\Filters;
use App\FiltersGroups;
use App\FiltersDiap;
use App\FiltersDef;
use App\FiltersDefGroups;
use App\FiltersDefParams;
use App\ProductPictures;

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
		
        if ($pr == 'param_type') {
           $id = $par['pt_id'];
           $name = $par['pt_name'];
           $table = $par['pt_table'];
           if (!$table) {
               $table = '';
           }

           $ord = ParamTypes::firstOrNew(['id' => $id]);
           $ord->id = $id;
           $ord->name = $name;
           $ord->table = $table;
           $ord->save();
           return 'OK'; 
        }

        if ($pr == 'cases') {
           $id = $par['c_id'];
           $name = $par['c_name'];
           $pict = $par['c_pict'];
           if (!$pict) {
                $pict = 'no_image.png';
           }
           
           $ord = Cases::firstOrNew(['id' => $id]);
           $ord->id = $id;
           $ord->name = $name;
           $ord->image = 'catalog/' . $pict;
           $ord->save();
           return 'OK'; 
        }

        if ($pr == 'brand') {
           $id = $par['br_id'];
           $name = $par['br_name'];
           $fullname = $par['br_fullname'];
           if (!$fullname) {
               $fullname = $name;
           }
           $cite = $par['br_syte'];
           if (!$cite) {
               $cite = '';
           }
           $opis = $par['br_opis'];
           $kod_sort = $par['br_kodsort'];
           $pict = $par['br_pict'];
           if (!$pict) {
                $pict = 'no_image.png';
           }
           $stat = 1;

           $chpu = $this->translit($name);
           
           $ord = Brands::firstOrNew(['id' => $id]);
           $ord->id = $id;
           $ord->name = $name;
           $ord->full_name = $fullname;
           $ord->cite = $cite;
           $ord->chpu = $chpu;
           $ord->comment = $opis;
           $ord->kod_sort = $kod_sort;
           $ord->logo = 'catalog/' . $pict;
           $ord->status = $stat;

           $ord->save();
           return 'OK'; 
        }

        if ($pr == 'party_params') {
           $prod_id = $par['pp_prodid']; 
           $p_id = $par['pp_id'];
           $id_val = '';
           if (isset($par['pp_params'])) {
                $id_val = $par['pp_params'];
           }
           

           PartyParams::where(['party_id1s' => $p_id])->delete();
           if ($id_val) {
            foreach ($id_val as $val) {
               $ar_val = explode('###', $val); 
               $pp = new PartyParams;
               $pp->product_id1s = $prod_id;
               $pp->party_id1s = $p_id;
               $pp->param_type_id = $ar_val[0];
               $pp->value = $ar_val[1];
               $pp->value_id = $ar_val[2];
               $pp->save();
            }
           }
           return 'OK'; 
        }

		if ($pr == 'soldout') {
            $id_prod = $par['so_id'];
            $sold = $par['so_qty'];
            
            $arr = SoldProduct::where(['product_id1s' => $id_prod])->first();
            if ($arr) {
                SoldProduct::where(['product_id1s' => $id_prod])
                    ->update(['sold' => $sold]);
            } else {
                $pp = new SoldProduct;
                $pp->product_id1s = $id_prod;
                $pp->sold = $sold;
                $pp->save();
            }
            return 'OK';
        }

        if ($pr == 'stock_party') {
            $id_prod = $par['sp_id'];
            $id_party = $par['sp_party'];
            $stock = $par['sp_stock'];
            
            $arr = StockParty::where(['product_id1s' => $id_prod, 'party_id1s' => $id_party])->first();
            if ($arr) {
                StockParty::where(['product_id1s' => $id_prod, 'party_id1s' => $id_party])
                    ->update(['stock' => $stock]);
            } else {
                $pp = new StockParty;
                $pp->product_id1s = $id_prod;
                $pp->party_id1s = $id_party;
                $pp->stock = $stock;
                $pp->save();
            }
            return 'OK';
        }

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
            $field = $par['ord_field'];
            if (!$field) {
                $field ='';
            }
            $napr = $par['ord_napr'];
            if (!$napr) {
                $napr ='';
            }
            $ord_type = $par['ord_type'];
            if (!$ord_type) {
                $ord_type ='';
            }
            $ord_param = $par['ord_param'];
            if (!$ord_param) {
                $ord_param = '';
            }
			$ord = Orders::firstOrNew(['id_1s' => $id]);
			$ord->id_1s = $id;
			$ord->name = $name;
			$ord->sort_order = $kod_sort;
			$ord->status = $status;
			$ord->sort_field = $field;
			$ord->sort_ord = $napr;
			$ord->sort_type = $ord_type;
            $ord->param_type_id = $ord_param;		
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

    if ($pr == 'fil_def') {
      $id = $par['f_id'];
      $name = $par['f_name'];
      $kod_sort = $par['f_kod_sort'];
      $status = $par['f_status'];

      $ord = FiltersDef::firstOrNew(['id_1s' => $id]);
      $ord->id_1s = $id;
      $ord->name = $name;
      $ord->sort_order = $kod_sort;
      $ord->status = $status;
      $ord->save();

      FiltersDefGroups::where('filters_def_id', $ord->id)->delete();

      if (isset($par['f_group'])) {
        foreach ($par['f_group'] as $value) {
          $grp = new FiltersDefGroups;
          $grp->filters_def_id = $ord->id;
          $grp->category_id = $value;
          $grp->save();
        }
      }

      FiltersDefParams::where('filters_def_id', $ord->id)->delete();

      if (isset($par['f_filters_id'])) {
        for ($i=0; $i < count($par['f_filters_id']); $i++) { 
          $grp = new FiltersDefParams;
          $grp->filters_def_id = $ord->id;
          $grp->filters_id_1s = $par['f_filters_id'][$i];
          $grp->value = $par['f_filters_val'][$i];
          $grp->save();
        }
      }

      return 'OK';
    }

    if ($pr == 'filter') {
      $id = $par['f_id'];
      $name = $par['f_name'];
      $kod_sort = $par['f_kod_sort'];
      $status = $par['f_status'];
      $field = $par['f_field'];
      if (!$field) {
        $field = '';
      }
      $ord_type = $par['f_type'];
      $ord_param = $par['f_param'];
      if (!$ord_param) {
        $ord_param = '';
      }
      $mark = $par['f_mark'];
      if (!$mark) {
        $mark = '';
      }

      $ord = Filters::firstOrNew(['id_1s' => $id]);
      $ord->id_1s = $id;
      $ord->name = $name;
      $ord->sort_order = $kod_sort;
      $ord->status = $status;
      $ord->filter_field = $field;
      $ord->filter_type = $ord_type;
      $ord->param_type_id = $ord_param;   
      $ord->mark = $mark;
      $ord->save();

      FiltersGroups::where('filters_id', $ord->id)->delete();

      if (isset($par['f_group'])) {

        foreach ($par['f_group'] as $value) {
          $grp = new FiltersGroups;
          $grp->filters_id = $ord->id;
          $grp->category_id = $value;
          $grp->save();
        }
      }

      FiltersDiap::where('filters_id', $ord->id)->delete();

      if (isset($par['f_diap'])) {

        foreach ($par['f_diap'] as $value) {
          $arr = explode('#-#', $value);
          $grp = new FiltersDiap;
          $grp->filters_id = $ord->id;
          $grp->value1 = $arr[0];
          $grp->descr1 = $arr[1];
          $grp->value2 = $arr[2];
          $grp->descr2 = $arr[3];
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
            $param = $par['grp_param'];
            if (!$param) {
                $param = '';
            }
			$grp = Groups::firstOrNew(['id_1s' => $id]);
			$grp->id_1s = $id;
			$grp->name = $name;
			$grp->sort_order = $kod_sort;
			$grp->status = $status;
            $grp->param_type_id = $param;
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
            $params = '';
            if (isset($par['pr_params'])) {
                $params = $par['pr_params'];
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

            PartyParams::where(['product_id1s' => $id_1s, 'party_id1s' => ''])->delete();
            if ($params) {
             foreach ($params as $val) {
               $ar_val = explode('###', $val); 
               $pp = new PartyParams;
               $pp->product_id1s = $id_1s;
               $pp->party_id1s = '';
               $pp->param_type_id = $ar_val[0];
               $pp->value = $ar_val[1];
               $pp->value_id = $ar_val[2];
               $pp->save();
             }
            }
            ProductPictures::where(['product_id1s' => $id_1s])->delete();
            if (isset($par['pr_pict'])) {
                foreach ($par['pr_pict'] as $val) {
                    $pp = new ProductPictures;
                    $pp->product_id1s = $id_1s;
                    $pp->party_id1s = '';
                    $pp->image = 'catalog/' . $val;
                    $pp->save();
                } 
            }

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
