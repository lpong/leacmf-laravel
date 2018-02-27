<?php

namespace App\Model;

use App\Library\ArrayHelp;
use App\Library\Tree;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $guard_name = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'nickname', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getNav()
    {
        if (empty($this->id)) {
            redirect(route('/'));
        }
        //自己
        $self = Permission::where(['name' => Route::currentRouteName()])->first()->toArray();
        //当前用户含有权限的菜单

        $rules = cache('sys:rule:' . $this->id);
        if (empty($rules)) {
            if ($this->hasRole('super admin')) {
                $rules = Permission::all()->toArray();
            } else {
                $rules = [];
                if ($this->roles) {
                    foreach ($this->roles as $role) {
                        $rules = array_merge($rules, $role->permissions->toArray());
                    }
                }
            }
            cache(['sys:rule:' . $this->id => $rules], env('APP_ENV') == 'local' ? 1 : 600);
        }
        $menu = cache('sys:menu:' . $this->id);
        if (empty($menu)) {
            $menu = [];
            if ($rules) {
                foreach ($rules as $rule) {
                    if ($rule['is_menu'] == 1) {
                        array_push($menu, $rule);
                    }
                }
            }
            //菜单
            $menu = ArrayHelp::list_to_tree($menu);
            cache(['sys:menu:' . $this->id => $menu], env('APP_ENV') == 'local' ? 1 : 600);
        }
        //面包屑
        $crumb      = Tree::getParents($rules, $self['id']);
        $parent_ids = [$self['id']];
        if ($crumb) {
            foreach ($crumb as $val) {
                array_push($parent_ids, $val['id']);
            }
        }

        return compact('self', 'menu', 'crumb', 'parent_ids');
    }
}
