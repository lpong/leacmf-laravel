<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2017/12/13
 * Time: 下午3:28
 */

namespace App\Library;

class Y
{
    //成功返回
    public static function success($msg = 'success', $data = [], $url = '')
    {
        return response()->json([
            'code' => 0,
            'msg'  => $msg,
            'url'  => $url,
            'data' => $data,
        ], 200);
    }

    //失败返回
    public static function error($msg = 'fail', $data = [], $url = '')
    {
        return response()->json([
            'code' => 1,
            'msg'  => $msg,
            'url'  => $url,
            'data' => $data,
        ], 200);
    }

    //table
    public static function table($data = [], $count = 0)
    {
        return response()->json([
            'code'  => 0,
            'msg'   => 'success',
            'count' => $count,
            'data'  => $data,
        ], 200);
    }

    //返回json
    public static function json()
    {
        $res       = new \stdClass();
        $res->code = 0;
        $res->msg  = 'success';
        $res->data = [];

        $field = func_get_args();

        switch (count($field)) {
            case 0:
                break;
            case 1:
                if (is_scalar($field[0])) {
                    $res->msg = $field[0];
                } else {
                    $res->data = $field[0];
                }
                break;
            case 2:
                $res->code = $field[0];
                $res->msg  = $field[1];
                break;
            case 3:
                $res->code = $field[0];
                $res->msg  = $field[1];
                $res->data = $field[2];
                break;
            default:
                $res->code = 500;
                $res->msg  = 'fail';
        }

        $res->code = intval($res->code);

        //将返回结果统一为字符串，方便客户端操作
        if (!empty($res->data)) {
            if (!is_array($res->data)) {
                $res->data = json_decode(json_encode($res->data), true);
            }
            array_walk_recursive($res->data, function (&$val) {
                $val = strval($val);
            });
        } else {
            $res->data = new \stdClass();
        }
        $code = $res->code >= 200 && $res->code <= 500 ? $res->code : 200;
        return response()->json($res, $code);
    }
}