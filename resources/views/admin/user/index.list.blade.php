<style type="text/css">
table .html {
    font-family: Arial;
    padding-right: 4px;
}
</style>
<table class="layui-table  text-center" lay-size="sm">
    <tbody>
        <tr>
            <th style="width: 48px">#</th>
            <th>
                <div class="text-left">标题</div>
            </th>
            <th>
                <div class="text-left">规则</div>
            </th>
            <th>图标</th>
            <td>菜单</td>
            <th style="width: 80px">排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <volist name="list" id="vo">
            <tr>
                <td>{$key+1}</td>
                <td>
                    <div class="text-left" title="{$vo.remark}"><span class="html">{$vo.html}</span> {$vo.title}</div>
                </td>
                <td>
                    <div class="text-left"> {$vo.name}</div>
                </td>
                <td><i class="fa {$vo.icon}"></i></td>
                <td><a href="{:url('menu',['id'=>$vo['id'],'is_menu'=>abs(1-$vo['is_menu'])])}" class="ajax-get" msg="0">{$vo.is_menu?'<span class="text-red">是</span>':'<span>否</span>'}</a></td>
                <td>
                    <input type="number" name="sort" data-url="{:url('sort',['id'=>$vo['id']])}" class="layui-input layui-input-sm input-sort" value="{$vo.sort}" data-val="{$vo.sort}">
                </td>
                <td><span class="{$vo.status?'text-red':''}"><i class="fa fa-circle"></i> {$vo.status?'正常':'禁用'}</span>
                </td>
                <td><a href="{:url('edit',['id'=>$vo['id']])}" class="layui-btn layui-btn-xs layui-btn-normal  ajax-form" title="修改规则">修改</a>
                    <a href="{:url('delete',['id'=>$vo['id']])}" title="删除" confirm="1" class="layui-btn layui-btn-xs layui-btn-danger  ajax-get">删除</a> </td>
            </tr>
        </volist>
    </tbody>
</table>