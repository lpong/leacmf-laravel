<div>
	@php 
	$type = isset($type)?$type:'image';
	$value = empty($value)?[]:$value;
	$title = isset($title)?$title:'图片';
	$bucket = isset($bucket)?$bucket:$type;
	$extensions = isset($extensions)?$extensions:'';
    $prefix = isset($prefix)?$prefix:'';
	$time = uniqid();
	$single = 'true';
	$upload_url = config('filesystems.disks.qiniu.upload_url');
	$token = App\Service\Qiniu::ins()->auth->uploadToken($bucket);
	if(substr($field,-2)=='[]'){
		$single='false';
	}
	if($single=='true' && $value){
		$value = [$value];
	}
	if(!$extensions){
		$extensions = $type=='image'?'gif,jpg,jpeg,bmp,png':'*';
	}
    $resource = isset($resource)?$resource:true;
	@endphp
    @if($resource)
        <!--引入CSS-->
        <link rel="stylesheet" type="text/css" href="/static/admin/plugins/webuploader/webuploader.css">
        <!--引入JS-->
        <script type="text/javascript" src="/static/admin/plugins/webuploader/webuploader.html5only.min.js"></script>
    @endif
    <div class="wu-example">
        <div id="filePicker-{{$time}}"><i class="fa fa-cloud-upload"></i>
            上传{{$title}}
        </div>
        <div id="fileList-{{$time}}" class="uploader-list">
        	@if($value)
        		@if($type=='image')
                    @foreach($value as $i=>$vo)
                        <div id="image-{{$time}}-{{$i}}" class="file-item thumbnail">
                            <img src="{{App\Library\Y::file($bucket,$vo)}}" style="width: 100px;height: 100px">
                            <div class="info">{{$vo}}</div>
                            <input type="hidden" name="{{$field}}" value="{{$vo}}">
                        </div>
                    @endforeach
                    @else
                    @foreach($value as $vo)
                        <div id="file-{{$time}}-{{$i}}" class="file">
                            <p class="text-yellow">{{$vo}}</p>
                            <input type="hidden" name="{{$field}}" value="{{$vo}}">
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
    @php //exit; @endphp
    @if($type=='image')
        <script type="text/javascript">
        (function() {
            var single = {{$single}};
            var $fileList = $('#fileList-{{$time}}')
            // 初始化Web Uploader
            var uploader = WebUploader.create({
                auto: true,
                server: '{{$upload_url}}?token={{$token}}',
                pick: '#filePicker-{{$time}}',
                duplicate: false,
                accept: {
                    title: '{{$title}}',
                    extensions: '{{$extensions}}'
                }
            });
            uploader.on('fileQueued', function(file) {
                var $li = $(
                        '<div id="' + file.id + '" class="file-item thumbnail">' +
                        '<img>' +
                        '<div class="info">' + file.name + '</div>' +
                        '<div class="delete"><a><i class="fa fa-times"></i></a></div>' +
                        '</div>'
                    ),
                    $img = $li.find('img');
                // $filePicker为容器jQuery实例
                if (single) {
                    $fileList.html($li);
                } else {
                    $fileList.append($li);
                }
                // 创建缩略图
                // 如果为非图片文件，可以不用调用此方法。
                // thumbnailWidth x thumbnailHeight 为 100 x 100
                uploader.makeThumb(file, function(error, src) {
                    if (error) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }
                    $img.attr('src', src);
                }, 100, 100);
            });

            uploader.on('uploadBeforeSend', function(obj, data, headers) {
                data.key = WebUploader.Base.guid('{{$prefix}}') + '.' + obj.file.ext;
            });

            // 文件上传过程中创建进度条实时显示。
            uploader.on('uploadProgress', function(file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress span');

                // 避免重复创建
                if (!$percent.length) {
                    $percent = $('<p class="progress"><span></span></p>')
                        .appendTo($li)
                        .find('span');
                }

                $percent.css('width', percentage * 100 + '%');
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function(file, response) {
                $('#' + file.id).addClass('upload-state-done');
                $('#' + file.id).append('<input type="hidden" name="{{$field}}" value="' + response.key + '">');
            });
            // 文件上传失败，显示上传出错。
            uploader.on('uploadError', function(file) {
                var $li = $('#' + file.id),
                    $error = $li.find('div.error');
                // 避免重复创建
                if (!$error.length) {
                    $error = $('<div class="error"></div>').appendTo($li);
                }

                $error.text('上传失败');
            });
            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on('uploadComplete', function(file) {
                $('#' + file.id).find('.progress').remove();
            });
            $fileList.on('dblclick', '.file-item img', function(event) {
                event.preventDefault();
                $(this).closest('.file-item').remove();
            });
            $fileList.on('click', '.delete a', function(event) {
                event.preventDefault();
                $(this).closest('.file-item').remove();
            });
        })();
        </script>
        @else
        <script type="text/javascript">
        (function() {
            var single = {{$single}};
            var $fileList = $('#fileList-{{$time}}')

            // 初始化Web Uploader
            var uploader = WebUploader.create({
                auto: true,
                server: '{{$upload_url}}?token={{$token}}',
                pick: '#filePicker-{{$time}}',
                chunked: false,
                chunkSize: 4194000,
                threads: 5,
                accept: {
                    title: '{{$title}}',
                    extensions: '{{$extensions}}'
                }

            });
            uploader.on('fileQueued', function(file) {
                if (single != 'false') {
                    $fileList.html('<div id="' + file.id + '" class="file"><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%;"><span>' + file.name + '</span> <i>等待上传</i></div><a href="javascript:;" class="remove">取消上传</a></div></div>')
                } else {
                    $fileList.append('<div id="' + file.id + '" class="file"><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%;"><span>' + file.name + '</span> <i>等待上传</i></div><a href="javascript:;" class="remove">取消上传</a></div></div>')
                }
            });
            uploader.on('uploadBeforeSend', function(obj, data, headers) {
                data.key = WebUploader.Base.guid('{{$prefix}}') + '.' + obj.file.ext;
            });
            // 文件上传过程中创建进度条实时显示。
            uploader.on('uploadProgress', function(file, percentage) {
                $('#' + file.id).find('.progress-bar i').text(parseInt(percentage * 100) + '%')
                $('#' + file.id).find('.progress-bar').css('width', percentage * 100 + '%');
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function(file, response) {
                if (single) {
                    $('#' + file.id).html('<p class="text-yellow">' + file.name + ' ( 上传成功 ) </p><input type="hidden" name="{{$field}}" value="' + response.key + '">');
                } else {
                    $('#' + file.id).append('<p class="text-yellow">' + file.name + ' ( 上传成功 ) <a href="javascript:;" title="删除" class="delete"><i class="fa fa-times"></i></a></p><input type="hidden" name="{{$field}}" value="' + response.key + '">');
                }
            });
            // 文件上传失败，显示上传出错。
            uploader.on('uploadError', function(file) {
                $('#' + file.id).html('<p class="text-red">' + file.name + ' ( 上传失败 ) </p>');
            });
            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on('uploadComplete', function(file) {
                $('#' + file.id).find('.progress').remove();
            });
            $fileList.on('click', '.delete', function(event) {
                event.preventDefault();
                $(this).closest('.file').remove();
            });
            $fileList.on('click', '.delete a', function(event) {
                event.preventDefault();
                $(this).closest('.file-item').remove();
            });
            $fileList.on('click', '.remove', function(event) {
                event.preventDefault();
                var file_id = $(this).closest('.file').attr('id');
                uploader.cancelFile(file_id, true)
                $(this).closest('.file').remove();
            });
        })();
        </script>
    @endif
</div>