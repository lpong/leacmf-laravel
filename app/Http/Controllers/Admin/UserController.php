<?php

namespace App\Http\Controllers\Admin;

use App\Library\Y;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //用户列表
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $keyword = trim($request->post('keyword', ''));
            $status  = intval($request->post('status', -1));
            $limit   = intval($request->post('limit', 10));
            $record  = DB::table('users')->whereIn('status', [0, 1])
                ->when($keyword, function ($query) use ($keyword) {
                    return $query->where('nickname', 'like', '%' . $keyword . '%');
                })
                ->when(in_array($status, [0, 1]), function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->paginate($limit);
            return Y::table($record->items(), $record->total());
        } else {
            return view('admin.user.index');
        }
    }

    //设置状态
    public function setStatus(Request $request)
    {
        $id   = intval($request->get('id', 0));
        $user = User::find($id);
        if (!$user) {
            return Y::error('用户不存在');
        }
        $user->status = 1 - $user->status;
        if ($user->save()) {
            return Y::json('操作成功');
        }
        return Y::json('操作失败');
    }

}
