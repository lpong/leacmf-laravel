layui.define(['layer', 'form', 'element', 'table', 'laydate'], function(exports) {
    var layer = layui.layer,
        element = layui.element,
        table = layui.table,
        laydate = layui.laydate,
        form = layui.form,
        $ = layui.$;

    var lea = {
        msg: function(msg) {
            var _msg = '';
            if (typeof msg === 'object') {
                $.each(msg, function(i, val) {
                    _msg += '<li style="text-align:left;list-syle-type:square">' + val + '</li>';
                });
            } else {
                _msg = msg;
            }
            return _msg;
        }
    };

    //全局方法
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    $(document).ajaxError(function(event, xhr) {
        if (xhr.status == 422) {
            layer.msg(lea.msg(responseJSON.message.errors || '数据校验失败'));
        } else if (xhr.status == 404) {
            layer.msg('请求的内容不存在');
        } else {
            layer.msg('服务器异常，请稍后再试~');
        }
    });

    //导航菜单
    $('.aside li>a').click(function(e) {
        e.stopPropagation();
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
        return false;
    });
    //侧边隐藏
    $('.ajax-flexible').click(function() {
        var href = $(this).attr('href');
        var dom = $('.layui-layout-admin');
        var menu = dom.attr('layui-layout') == 'closed' ? 'open' : 'closed';
        dom.attr('layui-layout', menu);
        $.get(href, 'menu=' + menu);
        return false;
    });

    //表格初始化
    $.fn.getList = function(callback, resetPage) {
        var that = this;
        var url = that.data('url') || window.location.href;
        var param = that.find('form').serialize();
        var page = that.data('page') || 1;
        if (resetPage) {
            page = 1;
        }
        param = 'page=' + page + '&' + param;
        $.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                data: param,
            })
            .done(function(html) {
                that.find('.data').empty().html(html);
                form.render();
                that.data('page', page);
            })
            .fail(function(xhr) {
                console.log(xhr.responseText);
                that.find('.data').empty().html('<p><i class="fa fa-warning"></i> 服务器异常，请稍后再试~</p>');
            })
            .always(function() {
                if (typeof callback === 'function') {
                    callback();
                }
            });
    };
    if ($('.data-list').length) {
        $('.data-list').getList(null, true);
    }

    //点击搜索
    $(document).on('click', '.search', function(event) {
        event.preventDefault();
        var that = $(this);
        that.html('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i>');
        that.closest('.data-list').getList(function() {
            that.html('<i class="layui-icon">&#xe615;</i>');
        });

    });
    //自动搜索
    form.on('select(data-list)', function(data) {
        $(data.elem).closest('.data-list').getList();
    });

    //分页
    $(document).on('click', '.layui-laypage-page>a', function() {
        var page = $(this).attr('lay-page');
        $(this).closest('.data-list').data('page', page).getList();
        return false;
    });
    //分页跳转
    $(document).on('click', '.layui-laypage-page .layui-laypage-btn', function() {
        var dom = $(this).closest('.data-list');
        var input = $(this).prev('input');
        var page = input.val();
        if (!page) {
            layer.msg('请输入页码');
            return false;
        }
        if (parseInt(page) > parseInt(input.attr('max')) || parseInt(page) < parseInt(input.attr('min'))) {
            layer.msg('页码范围为' + input.attr('min') + '~' + input.attr('max'));
            return false;
        }
        $(this).closest('.data-list').data('page', page).getList(href);
        return false;
    });

    //快速排序
    $(document).on('change', '.data-list .layui-input', function(event) {
        event.preventDefault();
        var self = $(this);
        var url = self.attr('href') || self.data('url');
        if (self.val() != self.attr('data-val')) {
            $.get(url, self.serialize(), function(res) {
                if (res.code != 1) {
                    layer.msg(lea2msg(lea.msg(res.msg)));
                    self.closest('.data-list').getList();
                } else {
                    self.attr('data-val', self.val());
                }
            });
        }
    });

    /**
     * 异步获取表单
     * 异步提交表单
     * 表单验证
     */
    $(document).on('click', '.ajax-form', function(event) {
        event.preventDefault();
        var self = $(this);
        if (self.attr('disabled')) return false;
        var url = self.attr('href') || self.data('url');
        if (!url) return;
        $.get(url, function(html) {
            if (typeof html === 'object') {
                layer.msg(html.msg);
                return false;
            }
            layer.open({
                type: 1,
                title: self.attr('title'),
                content: html,
                scrollbar: false,
                maxWidth: '80%',
                btn: ['确定', '取消'],
                yes: function(index, layero) {
                    if ($(layero).find('.layui-layer-btn0').attr('disabled')) {
                        return false;
                    }
                    $(layero).find('.layui-layer-btn0').attr('disabled', 'disabled');
                    var _form = $(layero).find('form');
                    $.ajax({
                            url: _form.attr('action') || '',
                            type: 'POST',
                            dataType: 'json',
                            data: _form.serialize(),
                        })
                        .done(function(res) {
                            if (res.code == 0) {
                                self.closest('.data-list').getList();
                                layer.msg(lea.msg(res.msg), { time: 1200 }, function() {
                                    layer.close(index);
                                });
                            } else {
                                var str = lea.msg(res.msg) || '服务器异常';
                                layer.msg(str);
                                $(layero).find('.layui-layer-btn0').removeAttr('disabled')
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            $(layero).find('.layui-layer-btn0').removeAttr('disabled')
                        });
                },
                btn2: function(index) {
                    layer.close(index);
                },
                success: function() {
                    form.render();
                }
            }, 'html');
        });
        return false;
    });

    /**
     * 异步url请求
     * 用户简单操作，如删除
     */
    $(document).on('click', '.ajax-get', function(event) {
        event.preventDefault();
        var self = $(this);
        var url = self.attr('href') || self.data('url');
        var title = self.attr('title') || '执行该操作';
        if (!url) return false;

        if (self.attr('confirm')) {
            layer.confirm('您确定要 <span style="color:#f56954">' + title + '</span> 吗？', function(index) {
                $.get(url, function(res) {
                    layer.msg(lea.msg(res.msg));
                    self.closest('.data-list').getList();
                });
            });

        } else {
            $.get(url, function(res) {
                var message = self.attr('msg') || 1;
                if (res.code == 0 || message == 1) {
                    layer.msg(lea.msg(res.msg));
                }
                self.closest('.data-list').getList();
            });
        }
        return false;
    })


    //监听table swtich操作
    form.on('switch(table-status)', function(obj) {
        var self = $(obj.elem);
        var table = self.closest('.data-table').data('id');
        $.ajax({
                url: self.data('href'),
                type: 'post',
                dataType: 'json'
            })
            .done(function(data) {
                if (data.code != 0) {
                    layer.msg(data.msg);
                    $(table).reload();
                }
            })
            .fail(function(xhr) {
                layer.msg('服务器异常，请稍后重试~');
                console.log(xhr.responseText);
                $(table).reload();
            });
    });

    /**
     *监听提普通交
     */
    form.on('submit(layform)', function(data) {
        var self = $(data.elem);
        if (self.attr('disabled')) {
            return false;
        }
        self.attr('disabled', 'disabled');
        $.ajax({
                url: data.form.action || '',
                type: 'POST',
                dataType: 'json',
                data: data.field
            })
            .done(function(res) {
                if (res.code == 0) {
                    layer.msg(lea.msg(res.msg), {
                        time: 1000,
                        icon: 6
                    }, function() {
                        if (res.url) window.location.href = res.url;
                    });
                } else {
                    layer.msg(lea.msg(res.msg), {
                        time: 1500,
                        icon: 5
                    });
                }
            })
            .fail(function() {
                layer.msg('服务器异常', {
                    time: 1500,
                    icon: 5
                });
            })
            .always(function() {
                self.removeAttr('disabled');
            });
        return false;
    });

    $('.laydate-range').each(function() {
        laydate.render({
            elem: this,
            type: 'date',
            range: '~'
        });
    });

    $('#refresh').click(function() {
        var self = $(this);
        var length = $('.data-list').length;
        if (self.attr('disabled') || length < 1) {
            return false;
        }
        self.attr('disabled', 'disabled');
        self.find('i').addClass('layui-anim').addClass('layui-anim-rotate').addClass('layui-anim-loop');
        $('.data-list').each(function(index, el) {
            $(this).getList(function() {
                console.log(index);
                if (index + 1 >= length) {
                    self.find('i').removeClass('layui-anim').removeClass('layui-anim-rotate').removeClass('layui-anim-loop');
                    self.removeAttr('disabled')
                }
            });
        });
    });
    //输出test接口
    exports('lea', lea);
});