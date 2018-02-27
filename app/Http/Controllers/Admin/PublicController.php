<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/1/18
 * Time: 14:51
 */

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Y;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * 登录
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $post      = $request->only(['username', 'password', 'captcha']);
            $validator = Validator::make($post, [
                'username' => 'required|max:32',
                'password' => 'required|min:6|max:16',
                'captcha'  => 'required|captcha'
            ], [
                'captcha' => '验证码错误'
            ]);
            if ($validator->fails()) {
                return Y::error($validator->errors());
            }
            unset($post['captcha']);
            if (Auth::guard('admin')->attempt($post, boolval($request->post('remember', '')))) {
                return Y::success('登录成功', [], route('/'));
            }
            return Y::error('用户验证失败');
        } else {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('/');
            }
            return view('admin.public.login');
        }
    }

}