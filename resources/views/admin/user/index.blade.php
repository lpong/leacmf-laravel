@extends('admin.layout') @section('content')
<div class="data-list" data-url="">
    <form class="layui-form inline-form">
        <div class="pull-left">
            <div class="layui-inline">
                <button class="layui-btn layui-btn-normal layui-btn-sm ajax-form" data-url="/admin/rbac/permission/add" title="添加权限"><i class="layui-icon">&#xe61f;</i> 添加权限</button>
            </div>
        </div>
        <div class="pull-right">
            <div class="layui-inline">
                <select name="pid" lay-filter="data-list">
                    <option value="">全部</option>
                    <volist name="list" id="vo">
                        <option value="{$vo.id}">{$vo.title}</option>
                    </volist>
                </select>
            </div>
            <div class="layui-inline">
                <button class="layui-btn layui-btn-sm layui-btn-normal search"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </form>
    <div class="data">
        <p><i class="fa fa-spinner fa-spin"></i> 加载中...</p>
    </div>
</div>
@endsection