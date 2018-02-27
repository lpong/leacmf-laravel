<div class="layui-card-body">
    <form class="layui-form" action="{{ url()->current() }}" style="width: 500px;" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="角色名称" value="" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色标识</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="角色标识" value="" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" placeholder="描述"></textarea>
            </div>
        </div>
    </form>
</div>