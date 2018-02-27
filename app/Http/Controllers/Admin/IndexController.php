<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/1/18
 * Time: 14:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index.index');
    }

    public function flexible(Request $request)
    {
        session(['menu_status' => $request->get('menu', 'open')]);
    }
}