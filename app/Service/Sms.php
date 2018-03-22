<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/1/30
 * Time: 14:34
 */

namespace App\Service;

use Illuminate\Support\Facades\DB;

class Sms
{

    //发送短信验证码
    public static function sendCode($mobile, $type = 'login')
    {
        if (app()->environment() == 'production') {
            //判断次数,5分钟内次数
            $count = DB::table('sms')->where('mobile', $mobile)->where('send_time', 'gt', time() - 300)->count();
            if ($count >= 3) {
                return '您发送短信太频繁，请稍后再试';
            }
            $count = Db::table('sms')->where('mobile', $mobile)->where('send_time', 'gt', time() - 24 * 60 * 60)->count();
            if ($count >= 10) {
                return '您今天发送短信太频繁，请以后再试';
            }

            $code = rand(100000, 999999);
        } else {
            $code = 123456;
        }
        $content = str_replace('{code}', $code, config('param.sms.template.code'));
        $ret     = app()->environment() !== 'production' ? true : self::send($mobile, $content);

        Db::table('sms')->insert([
            'mobile'      => $mobile,
            'type'        => $type,
            'status'      => $ret === true ? 1 : 0,
            'content'     => $content,
            'send_time'   => time(),
            'sms_ret_msg' => strval($ret)
        ]);
        cache()->set('sms:' . $type . ':' . $mobile, sha1($code), config('param.sms.ttl'));
        return $ret;
    }

    //验证短信沿着干嘛
    public static function check($mobile, $type, $code)
    {
        $key      = 'sms:' . $type . ':' . $mobile;
        $sms_code = cache($key);
        if (!$sms_code) {
            return '请先发送短信验证码';
        }
        if ($sms_code != sha1($code)) {
            return '验证码错误';
        }
        cache()->delete($key);
        return true;
    }


    public static function send($mobile, $content)
    {
        //这儿推送

    }

}