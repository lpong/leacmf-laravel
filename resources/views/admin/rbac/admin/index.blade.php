@extends('admin.layout') 
@section('content')
<div class="data-list" data-url="">
    <form class="layui-form inline-form">
        <div class="pull-left">
            <div class="layui-inline">
                <button class="layui-btn layui-btn-normal layui-btn-sm ajax-form" data-url="{{route('add-admin')}}" title="添加用户"><i class="layui-icon">&#xe61f;</i> 添加用户</button>
            </div>
        </div>
    </form>
    <div class="data">
        <p><i class="fa fa-spinner fa-spin"></i> 加载中...</p>
    </div>
</div>
@endsection