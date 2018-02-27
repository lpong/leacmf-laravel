@extends('admin.layout') @section('content')
<div class="panel panel-default panel-intro">
    <div class="panel-body">
        <div class="layui-tab layui-tab-brief" lay-filter="user">
            <ul class="layui-tab-title" id="LAY_mine">
                <li class="layui-this" lay-id="info">我的资料</li>
                <li lay-id="avatar">头像</li>
                <li lay-id="pass">密码</li>
            </ul>
            <div class="layui-tab-content" style="padding: 20px 0;">
                <div class="layui-form layui-form-pane layui-tab-item layui-show">
                    <form method="post" action="{{ route('me') }}">
                        <div class="layui-form-item">
                            <label for="L_email" class="layui-form-label">用户名</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{ $user->username }}" readonly="1" disabled="disabled" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_username" class="layui-form-label">昵称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" value="{{ $user->nickname }}" required="" lay-verify="required" autocomplete="off" value="{$info.nickname}" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">当前只有昵称可以修改！</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">创建时间</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{ $user->created_at }}" disabled="disabled" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">更新时间</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{ $user->updated_at }}" class="layui-input" disabled="disabled">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="layform">确认修改</button>
                        </div>
                    </form>
                </div>
                <div class="layui-form layui-form-pane layui-tab-item">
                    <div class="layui-form-item">
                        <div class="layui-form-item">
                            <div class="avatar-add">
                                <p>建议尺寸168*168，支持jpg、png、gif，最大不能超过30KB</p>
                                <div class="upload-img">
                                    <button type="button" class="layui-btn layui-btn-primary" id="test1">
                                        <i class="layui-icon">&#xe67c;</i>上传头像
                                    </button>
                                </div>
                                <div class="img">
                                    <img src="{{ asset($user->face) }}">
                                </div>
                                <span class="loading"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form layui-form-pane layui-tab-item">
                    <form class="form" action="{{ route('me') }}">
                        <div class="layui-form-item">
                            <label for="L_pass" class="layui-form-label">新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password" required="" lay-verify="required" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_repass" class="layui-form-label">确认密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password_confirmation" required="" lay-verify="required" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="layform">确认修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('script')
<script type="text/javascript">
layui.use(['upload', 'layer'], function() {
    var upload = layui.upload;
    //执行实例
    var uploadInst = upload.render({
        elem: '#test1',
        url: "{{ route('upload',['type'=>'face']) }}",
        exts:'jpg|png|gif|bmp|jpeg',
        field:'face',
        done: function(res) {
            var input = '#test1';
            $(input).closest('.avatar-add').find('.loading').hide();
            if (res.code == 0) {
                $.post("{{ route('me') }}", 'face=' + res.data.path, function(data) {
                    if (data.code == 0) {
                        $(input).closest('.avatar-add').find('img').attr('src', res.data.url);
                    } else {
                        layer.msg(data.msg);
                    }
                });
            } else {
                layer.msg(res.msg);
            }
        }

    });
});
</script>
@endsection