<?php
namespace App\Helpers\Pakula;
use Illuminate\Support\Facades\File; 
use App\Setting;
use Auth;

 
class Jsrender {
    public static function render($path, $state) {
    	$state['csrf'] = csrf_token();
    	$setts = Setting::all();
        $data = [];
        foreach ($setts as $sett) {
            $data[$sett->setting_id] = $sett->name;
        }
        $state['settings'] = $data;
        $state['auth'] = true; //Auth::check();
        if ($state['auth']) {
        	$state['userEmail'] = 'info@microstone.ru';//Auth::user()->email;
        } else {
        	$state['userEmail'] = '';
        }
        $data_str = json_encode($state);

        $renderer_source = File::get(base_path('node_modules/vue-server-renderer/basic.js'));
        $app_source = File::get(public_path('js/entry-server.js'));

        $v8 = new \V8Js();

        ob_start();

        $js =
<<<EOT
var process = { env: { VUE_ENV: "server", NODE_ENV: "production" } }; 
this.global = { process: process}; 
var url = "$path";
var state = JSON.parse('$data_str');

EOT;
        $v8->executeString($js);
        $v8->executeString($renderer_source);
        $v8->executeString($app_source);

        $ssr =  ob_get_clean();
        
        return $ssr;
    }
}