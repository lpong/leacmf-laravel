<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/1/30
 * Time: 14:35
 */

return [
    /**
     * 短信
     */
    'sms'          => [
        'account'  => '1tianhua888hy',
        'password' => '5l6WD4',
        'prefix'   => '【智慧社区】',
        'suffix'   => '',
        'ttl'      => 5,
        'template' => [
            'code' => '您的验证码是{code}，请在10分钟内填写。'
        ]
    ],

    //极光推送
    'jiguang.push' => [
        'app_key'       => 'a6d74512a6b73b3ad5df2198',
        'master_secret' => '5636132baf0394b7292872ad',
    ],

];