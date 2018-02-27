<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/2/5
 * Time: 16:14
 */

namespace App\Library;
class ArrayHelp
{
    /**
     * 数组转对象
     * @param $array
     * @return \stdClass
     */
    public static function array2object($array)
    {
        if (is_array($array)) {
            $obj = new \stdClass();
            foreach ($array as $key => $val) {
                $obj->$key = is_array($val) ? self::array2object($val) : $val;
            }
        } else {
            $obj = $array;
        }
        return $obj;
    }

    /**
     * 对象转数组
     * @param $object
     * @return mixed
     */
    public static function object2array($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }

    /**
     * 自定义排序
     * @param $array
     * @param $func
     * @return mixed
     */
    public static function customSort($array, $func)
    {
        uasort($array, $func);
        return $array;
    }


    /**
     * 把返回的数据集转换成Tree
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    public static function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent           = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    } else {
                        $tree[] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }


    public static function column($arr, $field)
    {
        if (empty($arr) || !is_array($arr)) return null;
        $temp = [];
        foreach ($arr as $v) {
            array_push($temp, $v[$field]);
        }
    }

}