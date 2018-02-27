<?php

namespace App\Http\Controllers\Admin;

use App\Library\Y;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    //本地上传
    public function upload(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'face'     => 'mimes:jpeg,bmp,png,gif|max:240',  //头像
            'cover'    => 'mimes:jpeg,bmp,png,gif|size:500', //大图
            'video'    => 'mimes:mp4,avi|size:40960',
            'audio'    => 'mimes:mp3|size:4096',
            'document' => 'mimes:doc,docx,xls,xlsx|size:2048', //文档
            'attach'   => 'mimes:jpeg,bmp,png,gif|size:4096', //附件
        ]);
        if ($validator->fails()) {
            return Y::error($validator->errors());
        }

        if (!$request->hasFile($type)) {
            return Y::error('上传的文件不能为空');
        }
        $file = $request->{$type};
        $path = $file->store($type . '/' . date('Ymd'));

        $path = 'uploads/' . $path;
        return Y::success('上传成功', [
            'path' => $path,
            'url'  => asset($path)
        ]);
    }
}
