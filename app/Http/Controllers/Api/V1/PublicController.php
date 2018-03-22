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

        //这儿写逻辑
        return Y::json('发送成功');
    }
}
