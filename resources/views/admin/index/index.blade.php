@extends('admin.layout') @section('content')
<fieldset class="layui-elem-field">
    <legend>系统信息</legend>
    <div class="layui-field-box">
        <table class="layui-table">
            <tbody>
                <tr>
                    <td>服务器操作系统：</td>
                    <td>{{ $sys_info['os'] }}</td>
                    <td>服务器域名/IP：</td>
                    <td>{{ $sys_info['domain'] }} [ {{ $sys_info['ip'] }} ]</td>
                    <td>服务器环境：</td>
                    <td>{{ $sys_info['web_server'] }}</td>
                </tr>
                <tr>
                    <td>PHP 版本：</td>
                    <td>{{ $sys_info['phpv'] }}</td>
                    <td>Mysql 版本：</td>
                    <td>{{ $sys_info['mysql_version'] }}</td>
                    <td>GD 版本</td>
                    <td>{{ $sys_info['gdinfo'] }}</td>
                </tr>
                <tr>
                    <td>文件上传限制：</td>
                    <td>{{ $sys_info['fileupload'] }}</td>
                    <td>最大占用内存：</td>
                    <td>{{ $sys_info['memory_limit'] }}</td>
                    <td>最大执行时间：</td>
                    <td>{{ $sys_info['max_ex_time'] }}</td>
                </tr>
                <tr>
                    <td>安全模式：</td>
                    <td>{{ $sys_info['safe_mode'] }}</td>
                    <td>Zlib支持：</td>
                    <td>{{ $sys_info['zlib'] }}</td>
                    <td>Curl支持：</td>
                    <td>{{ $sys_info['curl'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</fieldset>
<fieldset class="layui-elem-field">
    <legend>版本信息</legend>
    <div class="layui-field-box">
        <form class="layui-form layui-form-pane" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">项目名称</label>
                <div class="layui-input-block">
                    <input type="text" value="{{ env('APP_NAME') }}" readonly="1" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主框架</label>
                <div class="layui-input-block">
                    <input type="text" value="Laravel {{app()->version()}}" readonly="1" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">应用环境</label>
                <div class="layui-input-block">
                    <input type="text" value="{{ env('APP_ENV') }}" readonly="1" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">App Debug</label>
                <div class="layui-input-block">
                    <input type="text" value="{{ env('APP_DEBUG')?'true':'false' }}" readonly="1" class="layui-input">
                </div>
            </div>
        </form>
    </div>
</fieldset>
@endsection