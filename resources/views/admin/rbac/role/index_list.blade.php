<table class="layui-table  text-center" lay-size="sm">
    <tbody>
        <tr>
            <th style="width: 48px">#</th>
            <th>
                <div class="text-left">角色标题</div>
            </th>
            <th>
               <div class="text-left">角色名称</div>
            </th>
            <th style="width:40%" class="text-left">
                说明
            </th>
            <th>操作</th>
        </tr>
        @foreach ($record as $vo)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <div class="text-left">{{ $vo['title'] }}</div>
            </td>
            <td>
                <div class="text-left"> {{ $vo['name'] }}</div>
            </td>
            <td>{{ $vo['remark'] }}</td>
            <td>
                <a href="{{ route('edit-role',['id'=>$vo['id']]) }}" class="layui-btn layui-btn-xs layui-btn-normal  ajax-form" title="修改">修改</a>
                <a href="{{ route('assign-permission',['id'=>$vo['id']]) }}" class="layui-btn layui-btn-xs layui-btn-normal  ajax-form" title="权限">权限</a>
                <a href="{{ route('delete-role',['id'=>$vo['id']]) }}" title="删除" confirm="1" class="layui-btn layui-btn-xs layui-btn-danger  ajax-get">删除</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>