<?php

namespace App\Library;

/**
 * 无限分级类
 * @author Administrator
 *
 */
class Tree
{
    //组合一维数组
    public static function unlimitForLevel($cate, $html = '├─', $pid = 0, $level = 0)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level + 1;
                $v['html']  = str_repeat($html, $level);
                $arr[]      = $v;
                $arr        = array_merge($arr, self::unlimitForLevel($cate, $html, $v['id'], $level + 1));
            }
        }

        return $arr;
    }

    //组合多维数组
    public static function unlimitForLayer($cate, $pid = 0, $name = 'child')
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $v[$name] = self::unlimitForLayer($cate, $v['id'], $name);
                $arr[]    = $v;
            }
        }

        return $arr;
    }

    //传递子分类的id返回所有的父级分类
    public static function getParents($cate, $id)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr   = array_merge(self::getParents($cate, $v['pid']), $arr);
            }
        }

        return $arr;
    }

    //传递子分类的id返回所有的父级分类
    public static function getParentsIds($cate, $id)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['id'] == $id && $v['pid'] != 0) {
                $arr[] = $v['pid'];
                $arr   = array_merge(self::getParentsIds($cate, $v['pid']), $arr);
            }
        }

        return $arr;
    }

    //传递父级id返回所有子级id
    public static function getChildsId($cate, $pid)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v['id'];
                $arr   = array_merge($arr, self::getChildsId($cate, $v['id']));
            }
        }

        return $arr;
    }

    //传递父级id返回所有子级分类
    public static function getChilds($cate, $pid)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $arr   = array_merge($arr, self::getChilds($cate, $v['id']));
            }
        }

        return $arr;
    }

}
