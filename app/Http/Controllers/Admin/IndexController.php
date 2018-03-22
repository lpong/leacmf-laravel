<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/1/18
 * Time: 14:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Y;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //首页控制台
    public function index()
    {
        $sys_info = cache('sys_cache_server_info');
        if (!$sys_info) {
            $sys_info = $this->getServerInfo();
            cache(['sys_cache_server_info' => $sys_info], 10 * 60);
        }
        return view('admin.index.index', [
            'sys_info' => $sys_info
        ]);
    }

    public function flexible(Request $request)
    {
        session(['menu_status' => $request->get('menu', 'open')]);
    }

    /**
     * 获取系统信息
     * @return mixed
     */
    protected function getServerInfo()
    {
        $sys_info['os']           = PHP_OS;
        $sys_info['zlib']         = function_exists('gzclose') ? 'YES' : 'NO'; //zlib
        $sys_info['safe_mode']    = (boolean)ini_get('safe_mode') ? 'YES' : 'NO'; //safe_mode = Off
        $sys_info['timezone']     = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl']         = function_exists('curl_init') ? 'YES' : 'NO';
        $sys_info['web_server']   = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']         = phpversion();
        $sys_info['ip']           = GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload']   = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown';
        $sys_info['max_ex_time']  = @ini_get("max_execution_time") . 's'; //脚本最大执行时间
        $sys_info['domain']       = $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit'] = ini_get('memory_limit');
        $dbPort                   = Config::get('database.prefix');
        $dbHost                   = Config::get('database.prefix');
        $dbHost                   = empty($dbPort) || $dbPort == 3306 ? $dbHost : $dbHost . ':' . $dbPort;

        $musql_version             = DB::select('select version() as ver');
        $sys_info['mysql_version'] = $musql_version[0]->ver;
        if (function_exists("gd_info")) {
            $gd                 = gd_info();
            $sys_info['gdinfo'] = $gd['GD Version'];
        } else {
            $sys_info['gdinfo'] = "未知";
        }

        return $sys_info;
    }

    //清空缓存
    public function flush()
    {
        Cache::flush();
        return Y::success('缓存已清除');
    }
}