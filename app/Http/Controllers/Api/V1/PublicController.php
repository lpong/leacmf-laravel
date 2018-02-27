<?php

namespace App\Http\Controllers\Api\V1;

use App\Library\Y;
use App\Model\User;
use App\Service\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{

    /**
     * 发送短信
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function sendSms(Request $request)
    {
        $post      = $request->only(['mobile', 'mark', 'sign', 'rand_str']);
        $validator = Validator::make($post, [
            'mobile'   => 'required|regex:/^1[34578][0-9]{9}$/',
            'mark'     => 'required|in:login,signUp,editMobile,forgetPassword',
            'sign'     => 'required|alpha_num|size:40',
            'rand_str' => 'required|alpha_num|size:32'
        ]);
        extract($post);
        if ($validator->fails()) {
            return Y::json($validator->errors());
        }

        $cache_key = md5($rand_str);
        if (Cache::get($cache_key)) {
            return Y::json(1003, '令牌错误');
        }
        if (sha1(md5($mobile . $rand_str . date('Ymd'))) !== $sign) {
            return Y::json(1003, '数据校验异常');
        }

        if ($mark == 'signUp' || $mark == 'editMobile') {
            //判断该用户是否已注册
            if (User::where('mobile', $mobile)->first()) {
                return Y::json(1101, '该手机号已注册');
            }
        }
        if ($mark == 'forgetPassword') {
            //判断该用户是否已注册
            if (!User::where('mobile', $mobile)->first()) {
                return Y::json(1101, '手机号未注册');
            }
        }
        $result = Sms::sendCode($mobile, $mark);
        if ($result !== true) {
            return Y::json(1112, $result);
        }
        cache()->set($cache_key, time(), 7 * 24 * 3600);
        return Y::json('发送成功');
    }
}
