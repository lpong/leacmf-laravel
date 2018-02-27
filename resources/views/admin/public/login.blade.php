<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="utf-8">
    <title>layuiAdmin - 单页面后台管理模板系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/static/admin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/css/style.css" media="all">
</head>

<body>
    <div class="login layui-anim-up">
        <div class="login-main">
            <div class="login-box login-header">
                <h2>{{ config('app.name')}}</h2>
                <p>App后台管理系统</p>
            </div>
            <div class="login-box login-body">
                <form action="" class=" layui-form">
                    <div class="layui-form-item">
                        <label class="login-icon layui-icon layui-icon-username" for="username"></label>
                        <input type="text" name="username" id="username" maxlength="32" lay-verify="required" placeholder="用户名" class="layui-input">
                    </div>
                    <div class="layui-form-item">
                        <label class="login-icon layui-icon layui-icon-password" for="password"></label>
                        <input type="password" name="password" id="password" maxlength="16" minlength="6" lay-verify="required" placeholder="密码" class="layui-input">
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-row">
                            <div class="layui-col-xs7">
                                <label class="login-icon layui-icon layui-icon-vercode" for="captcha"></label>
                                <input type="text" name="captcha" maxlength="4" minlength="4" id="captcha" lay-verify="required" placeholder="图形验证码" class="layui-input">
                            </div>
                            <div class="layui-col-xs5">
                                <div style="margin-left: 10px;">
                                    <img src="" class="login-codeimg" id="vercode">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="layui-form-item" style="margin-bottom: 20px;">
                        <input type="checkbox" name="remember" value="1" lay-skin="primary" title="记住密码">
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="layform">登 入</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="login-footer">
        <hr>
        <p><span>Copyright©2014-2017 by 北京天华新瑞科技有限公司</span></p>
    </div>
    <script src="/static/admin/js/jquery.min.js"></script>
    <script src="/static/admin/layui/layui.js"></script>
    <script type="text/javascript">
    layui.config({
        base: '/static/admin/js/'
    }).use('lea');
    $(document).ready(function() {
        $('#vercode').click(function() {
            var src = "{{ captcha_src('flat') }}";
            $(this).attr('src', src + '?' + Math.random());
        });
        $('#vercode').click();
    });
    </script>
</body>

</html>