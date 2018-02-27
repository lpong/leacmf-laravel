<?php

namespace App\Http\Controllers\Admin;

use App\Library\Y;
use App\Model\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * 用户列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $record = Admin::all()->toArray();
            return view('admin.rbac.admin.index_list', [
                'record' => $record
            ]);
        } else {
            return view('admin.rbac.admin.index');
        }
    }

    //添加权限
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $post      = $request->post();
            $validator = Validator::make($post, [
                'username' => 'required|unique:admins|max:32',
                'nickname' => 'required|max:64',
                'password' => 'required|min:6|max:16',
                'roles'    => 'array'
            ]);
            if ($validator->fails()) {
                return Y::error($validator->errors());
            }
            $post['password'] = bcrypt($post['password']);
            $admin            = Admin::create($post);
            if ($admin && $post['roles']) {
                $admin->syncRoles($post['roles']);
            }
            return Y::success('添加成功');
        } else {
            $roles = Role::all();
            return view('admin.rbac.admin.add', [
                'roles' => $roles
            ]);
        }
    }

    //修改权限
    public function edit(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $post      = $request->only(['nickname', 'password', 'roles']);
            $validator = Validator::make($post, [
                'nickname' => 'required|max:64',
                'roles'    => 'array'
            ]);
            if ($validator->fails()) {
                return Y::error($validator->errors());
            }
            if ($id == 1) {
                return Y::error('超级管理员无法修改');
            }

            $admin = Admin::find($id);
            if (empty($post['password'])) {
                unset($post['password']);
            } else {
                $post['password'] = bcrypt($post['password']);
            }
            if ($admin->update($post)) {
                $admin->roles()->detach();
                if (!empty($post['roles'])) {
                    $admin->syncRoles($post['roles']);
                }
                return Y::success('更新成功');
            }
            return Y::success('更新失败');
        } else {
            //当前权限
            $admin     = Admin::findOrFail($id);
            $roles     = Role::all();
            $has_roles = [];
            if ($admin->roles) {
                $has_roles = $admin->roles->map(function ($role) {
                    return $role->id;
                })->toArray();
            }
            return view('admin.rbac.admin.edit', [
                'admin'     => $admin,
                'roles'     => $roles,
                'has_roles' => $has_roles,
            ]);
        }
    }

    //删除
    public function delete($id)
    {
        if (Admin::destroy($id) > 0) {
            return Y::success('删除成功');
        }
        return Y::error('删除失败');
    }

    //个人信息
    public function me(Request $request)
    {
        if ($request->isMethod('post')) {
            $post      = $request->post();
            $validator = Validator::make($post, [
                'nickname' => 'max:64',
                'face'     => 'max:128',
                'password' => 'min:6|max:16|confirmed',
            ]);
            if ($validator->fails()) {
                return Y::error($validator->errors());
            }
            unset($post['password_confirmation']);
            $data = [];
            foreach ($post as $key => $val) {
                if (!empty($val)) {
                    $data[$key] = $val;
                }
            }
            if (isset($data['password'])) {
                $data['password'] = encrypt($data['password']);
            }
            if (Admin::where('id', $request->user()->id)->update($data) > 0) {
                return Y::success('修改成功');
            }
            return Y::error('修改失败');
        } else {
            return view('admin.rbac.admin.me', [
                'user' => Auth::user()
            ]);
        }
    }

}
