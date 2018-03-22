<div>
    @php
        $id = isset($id)?$id:$field;
        $value = isset($value)?$value:'在这儿编辑内容。。。';
        $width = isset($width)?$width:'100%';
        $height = isset($height)?$height:'200px';
        $resource = isset($resource)?$resource:true;
    @endphp
    @if($resource)
        <!-- 样式文件 -->
        <link rel="stylesheet" href="/static/admin/plugins/umeditor/themes/default/css/umeditor.css">
        <!-- 配置文件 -->
        <script type="text/javascript" src="/static/admin/plugins/umeditor/umeditor.config.js"></script>
        <!-- 编辑器源码文件 -->
        <script type="text/javascript" src="/static/admin/plugins/umeditor/umeditor.js"></script>
        <!-- 语言包文件 -->
        <script type="text/javascript" src="/static/admin/plugins/umeditor/lang/zh-cn/zh-cn.js"></script>
    @endif
    <script id="{{$id}}" name="{{$field}}" style="width:{{$width}};height:{{$height}};" type="text/plain">{!! $value !!}</script>
    <script type="text/javascript">
    $(function() {
        window.um = UM.getEditor('{{$id}}', {
            imageUrl: "{{ route('upload-editor') }}"
        });
    });
    </script>
</div>