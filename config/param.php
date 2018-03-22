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
        'account'  => '',
        'password' => '',
        'prefix'   => '',
        'suffix'   => '',
        'ttl'      => 5,
        'template' => [
            'code' => '您的验证码是{code}，请在10分钟内填写。'
        ]
    ],

    //极光推送
    'jiguang.push' => [
        'app_key'       => '',
        'master_secret' => '',
    ],

];