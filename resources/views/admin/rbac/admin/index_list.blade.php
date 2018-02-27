<table class="layui-table  text-center" lay-size="sm">
    <tbody>
        <tr>
            <th style="width: 48px">#</th>
            <th>
                <div class="text-left">用户名</div>
            </th>
            <th>
                <div class="text-left">昵称</div>
            </th>
            <th class="text-left">
                创建时间
            </th>
            <th>操作</th>
        </tr>
        @foreach ($record as $vo)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <div class="text-left">{{ $vo['username'] }}</div>
            </td>
            <td>
                <div class="text-left"> {{ $vo['nickname'] }}</div>
            </td>
            <td>{{ $vo['created_at'] }}</td>
            <td>
                <a href="{{ route('edit-admin',['id'=>$vo['id']]) }}" class="layui-btn layui-btn-xs layui-btn-normal  ajax-form" title="修改信息">修改</a>
                <a href="{{ route('delete-admin',['id'=>$vo['id']]) }}" title="删除" confirm="1" class="layui-btn layui-btn-xs layui-btn-danger  ajax-get">删除</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>