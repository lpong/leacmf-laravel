@extends('admin.layout') @section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <!-- 填充内容 -->
            <div class="layui-card">
                <div class="layui-card-header">系统信息</div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <tbody>
                            <tr>
                                <td>服务器操作系统：</td>
                                <td>Linux</td>
                                <td>服务器域名/IP：</td>
                                <td>192.168.1.150:816 [ ]</td>
                                <td>服务器环境：</td>
                                <td>nginx/1.12.2</td>
                            </tr>
                            <tr>
                                <td>PHP 版本：</td>
                                <td>7.1.11</td>
                                <td>Mysql 版本：</td>
                                <td>5.5.55-log</td>
                                <td>GD 版本</td>
                                <td>bundled (2.1.0 compatible)</td>
                            </tr>
                            <tr>
                                <td>文件上传限制：</td>
                                <td>2M</td>
                                <td>最大占用内存：</td>
                                <td>128M</td>
                                <td>最大执行时间：</td>
                                <td>30s</td>
                            </tr>
                            <tr>
                                <td>安全模式：</td>
                                <td>NO</td>
                                <td>Zlib支持：</td>
                                <td>YES</td>
                                <td>Curl支持：</td>
                                <td>YES</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">版本信息</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">Laravel</label>
                            <div class="layui-input-block">
                                <input type="text" value="5.5" readonly="1" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" pane>
                            <label class="layui-form-label">单选框</label>
                            <div class="layui-input-block">
                                <input type="radio" name="sex" value="男" title="男">
                                <input type="radio" name="sex" value="女" title="女" checked>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header"></div>
                <div class="layui-card-body"></div>
            </div>
        </div>
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header"></div>
                <div class="layui-card-body"></div>
            </div>
        </div>
        <div class="layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-header"></div>
                <div class="layui-card-body"></div>
            </div>
        </div>
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header"></div>
                <div class="layui-card-body"></div>
            </div>
        </div>
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header"></div>
                <div class="layui-card-body"></div>
            </div>
        </div>
        <div class="layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-header"></div>
                <div class="layui-card-body"></div>
            </div>
        </div>
    </div>
</div>
@endsection