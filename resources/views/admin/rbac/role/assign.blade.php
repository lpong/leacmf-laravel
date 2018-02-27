@extends('admin.layout') @section('content')
<div class="">
    <style type="text/css">
    .rules select {
        font-size: 12px;
        border: 1px solid #eee;
        width: 400px;
        padding: 4px;
        box-sizing: border-box;
        font-family: Arial
    }
    </style>
    <div class="rules">
    </div>
    <hr>
    <a href="{{ route('roles') }}" class="layui-btn layui-btn-primary layui-btn-small"><i class="fa fa-history"></i> 返回</a>
</div>
@endsection @section('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{ url()->current() }}";
    //更新已选未选
    var updateRules = function() {
        $.get(url, 'ajax=1', function(data) {
            $('.rules').html(data);
        });
    };
    updateRules();
    //更新
    $(document).on('click', '#to-right', function() {
        var rules = $('#all').val();
        if (!rules) {
            return;
        }
        $.post(url, { operate: 'add', rules: rules }, function(data) {
            if (data.code == 0) {
                updateRules();
            } else {
                layer.msg(data.msg);
            }
        });
    });
    //更新
    $(document).on('click', '#to-left', function() {
        var rules = $('#has_permissions').val();
        if (!rules) {
            return;
        }
        $.post(url, { operate: 'remove', rules: rules }, function(data) {
            if (data.code == 0) {
                updateRules();
            } else {
                layer.msg(data.msg);
            }
        });
    });
});
</script>
@endsection