<div class="layui-card-body">
    <form class="layui-form" action="{{ url()->current() }}" style="width: 500px;" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">父级权限</label>
            <div class="layui-input-block">
                <select name="pid" lay-search>
                    <option value="0">顶级权限</option>
                    @foreach ($list as $vo)
                    <option value="{{$vo['id']}}" @if ($info->pid==$vo['id']) selected="selected" @endif >{{$vo['html']}} {{$vo['title']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="标题" value="{{ $info->title }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="路由名称" value="{{ $info->name }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图标</label>
            <div class="layui-input-inline">
                <input type="text" name="icon" placeholder="fa fa-edit" value="{{ $info->icon }}" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">支持font-awesome字体</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="number" name="sort" value="{{ $info->sort }}" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">是否菜单</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_menu" value="1" lay-text="是|不是" lay-skin="switch" @if ($info->is_menu==1) checked @endif >
                </div>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">参数</label>
            <div class="layui-input-block">
                <input type="text" name="param" placeholder="参数，http_build_query格式" value="{{ $info->param }}" class="layui-input">
            </div>
        </div>
    </form>
</div>