
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>欢迎使用 {{ env('APP_NAME') }} 后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/style.css">
</head>

<body>
    <div class="login">
        <div class="layui-card">
            <div class="layui-card-header">
                欢迎使用 <b style="font-family:Monaco,Consolas">{{ env('APP_NAME') }}</b> 后台管理系统
            </div>
            <div class="layui-card-body">
                <form class="layui-form  layui-form-pane">
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户名</label>
                        <div class="layui-input-block">
                            <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input ">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input ">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">验证码</label>
                        <div class="layui-input-inline" style="width:105px">
                            <input type="text" name="captcha" maxlength="4" placeholder="验证码" autocomplete="off" class="layui-input ">
                        </div>
                        <div class="layui-input-inline" style="width:100px">
                            <img src="{{captcha_src('flat')}}" alt="captcha" id="captcha-src" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-normal layui-btn-fluid" lay-submit lay-filter="*">登 录</button>
                    </div>
                </form>
                <p>- <b style="font-family:Monaco,Consolas;font-weight:300"> LeaCMF</b>  后台开发框架 -</p>
            </div>
        </div>
    </div>
    <script src="/static/admin/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer', 'element'], function () {
            var form = layui.form,
                layer = layui.layer,
                $ = layui.$;

            $('#captcha-src').click(function () {
                var str = '{{captcha_src('flat')}}';
                $(this).attr('src', str + '?' + Math.random());
            });

            form.on('submit(*)', function (data) {
                $.post('{{ url()->current() }}', data.field, function (res) {
                    layer.msg(res.msg);
                    if (res.code == 0) {
                        window.location.href = res.url;
                    } else {
                        $('#captcha-src').click();
                    }
                });
                return false;
            });

        });
    </script>
</body>

</html>
