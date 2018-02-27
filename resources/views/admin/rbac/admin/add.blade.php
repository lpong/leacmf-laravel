<div class="layui-card-body">
    <form class="layui-form" action="{{ url()->current() }}" style="width: 500px;" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input type="text" name="username" placeholder="用户名" value="" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-block">
                <input type="text" name="nickname" placeholder="昵称" value="" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" placeholder="密码" value="" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属角色</label>
            <div class="layui-input-block">
                @foreach($roles as $role)
                     @if ($role->id!=1)
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" lay-skin="primary" title="{{ $role->title }}">
                    @endif
                @endforeach
            </div>
        </div>
    </form>
</div>