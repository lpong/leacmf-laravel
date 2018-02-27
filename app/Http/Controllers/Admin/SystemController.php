<?php

namespace App\Http\Controllers\Admin;

use App\Library\Tree;
use App\Library\Y;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class SystemController extends Controller
{
    //权限列表
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $record = Permission::all()->toArray();
            $record = Tree::unlimitForLevel($record);
            return view('admin.rbac.permission.index_list', [
                'record' => $record
            ]);
        } else {
            return view('admin.rbac.permission.index');
        }
    }

    //添加权限
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $post      = $request->post();
            $validator = Validator::make($post, [
                'title' => 'required|max:64',
                'name'  => 'required|max:64'
            ]);
            if ($validator->fails()) {
                return Y::error($validator->errors());
            }
            $post['guard_name'] = 'admin';
            try {
                Permission::create($post);
            } catch (\Exception $e) {
                return Y::error($e->getMessage());
            }
            return Y::success('添加成功');
        } else {
            //获取层次权限
            $list = Permission::all()->toArray();
            $list = Tree::unlimitForLevel($list);
            return view('admin.rbac.permission.add', [
                'list' => $list
            ]);
        }
    }

    //修改权限
    public function edit(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $post      = $request->post();
            $validator = Validator::make($post, [
                'id'    => 'required',
                'title' => 'required|max:64',
                'name'  => 'required|max:64'
            ]);
            if ($validator->fails()) {
                return Y::error($validator->errors());
            }
            try {
                $result = Permission::where('id', $id)->update($post);
            } catch (\Exception $e) {
                return Y::error($e->getMessage());
            }
            if ($result) {
                return Y::success('更新成功');
            }
            return Y::success('更新失败');
        } else {
            //获取层次权限
            $list = Permission::all()->toArray();
            $list = Tree::unlimitForLevel($list);

            //当前权限
            $info = Permission::findOrFail($id);
            return view('admin.rbac.permission.edit', [
                'list' => $list,
                'info' => $info
            ]);
        }
    }

    //是否菜单切换
    public function menu(Request $request, $id)
    {
        $is_menu = intval($request->get('is_menu'));
        if (Permission::where('id', $id)->update(['is_menu' => $is_menu]) > 0) {
            return Y::success('设置成功');
        }
        return Y::error('设置失败');
    }

    //排序
    public function sort(Request $request, $id)
    {
        $sort = intval($request->get('sort'));
        if (Permission::where('id', $id)->update(['sort' => $sort]) > 0) {
            return Y::success('设置成功');
        }
        return Y::error('设置失败');
    }

    //删除
    public function delete($id)
    {
        if (Permission::destroy($id) > 0) {
            return Y::success('删除成功');
        }
        return Y::error('删除失败');
    }
}
