$(document).ready(function() {
    //导航菜单
    $('.aside li>a').click(function() {
        var next = $(this).next('dl');
        if (!$(this).next('dl').length) {
            return;
        }
        if (next.css('display') == 'none') {
            $(this).addClass('active');
            next.slideDown(500);
        } else {
            next.slideUp(500);
            $(this).removeClass('active');
        }
    });
});
layui.use(['element'], function() {
    var element = layui.element;
});