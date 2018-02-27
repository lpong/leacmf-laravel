<?php

namespace App\Http\Controllers\Admin;

use App\Library\Tree;
use App\Library\Y;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //权限列表
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $record = Role::all()->toArray();
            return view('admin.rbac.role.index_list', [
                'record' => $record
            ]);
        } else {
            return view('admin.rbac.role.index');
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
                Role::create($post);
            } catch (\Exception $e) {
                return Y::error($e->getMessage());
            }
            return Y::success('添加成功');
        } else {
            return view('admin.rbac.role.add');
        }
    }

    //修改权限
    public function edit(Request $request, $id)
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
            try {
                $result = Role::where('id', $id)->update($post);
            } catch (\Exception $e) {
                return Y::error($e->getMessage());
            }
            if ($result) {
                return Y::success('更新成功');
            }
            return Y::success('更新失败');
        } else {
            //当前权限
            $info = Role::findOrFail($id);
            return view('admin.rbac.role.edit', [
                'info' => $info
            ]);
        }
    }

    //删除
    public function delete($id)
    {
        if (Role::destroy($id) > 0) {
            return Y::success('删除成功');
        }
        return Y::error('删除失败');
    }

    //分配权限
    public function assign(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $operate = $request->post('operate');
            $rules   = (array)$request->post('rules');
            $role    = Role::findById($id);

            if (empty($rules)) {
                return Y::error('请选择权限');
            }

            if ($operate == 'add') {
                foreach ($rules as $rule) {
                    $role->givePermissionTo($rule);
                }
            } else {
                foreach ($rules as $rule) {
                    $role->revokePermissionTo($rule);
                }
            }

            return Y::success();
        } else {
            //获取该角色没有的权限
            $role            = Role::findById($id);
            $has_permissions = $role->permissions->toArray();
            $ids             = [];
            if ($has_permissions) {
                foreach ($has_permissions as $permission) {
                    array_push($ids, $permission['id']);
                }
            }
            //所有权限
            $permissions = Permission::all()->toArray();
            return view($request->get('ajax', 0) ? 'admin.rbac.role.rules' : 'admin.rbac.role.assign', [
                'role'            => $role,
                'ids'             => $ids,
                'permissions'     => Tree::unlimitForLevel($permissions),
                'has_permissions' => $has_permissions
            ]);

        }
    }
}
