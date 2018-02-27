<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2017/12/30
 * Time: 14:00
 */

namespace App\Library;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and')
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(think_auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(think_auth_group 定义了用户组权限)
 * 4，支持规则表达式。
 *      在think_auth_rule 表中定义一条规则，condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100
 * 表示用户的分数在5-100之间时这条规则才会通过。
 */
class Rbac
{

    /**
     * user
     * @var
     */
    protected $user;

    /**
     * @var object 对象实例
     */
    protected static $instance;

    //默认配置
    protected $config = [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'auth_group', // 用户组数据表名
        'auth_group_access' => 'auth_group_access', // 用户-用户组关系表
        'auth_rule'         => 'auth_rule', // 权限规则表
        'auth_user'         => 'admin', // 用户信息表
    ];

    /**
     * 类架构函数
     * Auth constructor.
     */
    public function __construct()
    {
        if ($config = Config::get('rbac')) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * 初始化
     * @param array $options
     * @return object|static
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * 登录
     * @param null $admin
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($admin = null)
    {
        if (is_numeric($admin)) {
            $admin = DB::table($this->config['auth_user'])->find($admin);
            unset($admin['password'], $admin['token']);
        }

        if ($admin) {
            session('admin', $admin);
            return true;
        }
        return false;
    }

    public function logout()
    {
        session('admin', null);
        $this->user = null;
        return true;
    }

    /**
     * 获取path
     * @return string
     */
    public function getPath()
    {
        return Request::route()->uri();
    }

    /**
     * 校验url，是否需要用户验证
     * @return bool
     */
    public function checkPublicUrl()
    {
        $urls = $this->config['public_url'];
        if (in_array($this->getPath(), $urls)) {
            return true;
        }
        return false;
    }


    /**
     * 检查是否登录
     * @return bool
     */
    public function isLogin()
    {
        return !!$this->user();
    }

    public function refresh()
    {
        return $this->login($this->getUserId());
    }


    /**
     * 当前登录用户
     * @return mixed|null
     */
    public function user()
    {
        $this->user = !empty($this->user) ? $this->user : session('admin');
        return $this->user;
    }

    /**
     * 获取用户id
     * @return mixed
     */
    public function getUserId()
    {
        $user = $this->user();
        return $user ? $user->id : null;
    }

    /**
     * 检查权限
     * @param null $name 需要验证的规则列表,支持|分隔的权限规则或索引数组
     * @param null $uid 要验证的用户，默认当前的用户
     * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @param string $mode 可以为url或normal
     * @return bool 通过验证返回true;失败返回false
     */
    public function check($name = null, $uid = null, $relation = 'or', $mode = 'url')
    {
        if (!$this->config['auth_on']) {
            return true;
        }

        is_null($name) && $name = $this->getPath();
        is_null($uid) && $uid = $this->getUserId();

        if (in_array($name, $this->config['allow_visit'])) {
            return true;
        }
        // 获取用户需要验证的所有有效规则列表
        $rulelist = $this->getRuleList($uid);
        if (in_array('*', $rulelist)) {
            return true;
        }

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, '|') !== false) {
                $name = explode('|', $name);
            } else {
                $name = [$name];
            }
        }
        $list = []; //保存验证通过的规则名
        if ('url' == $mode) {
            $_REQUEST = unserialize(strtolower(serialize(Request::param())));
        }
        foreach ($rulelist as $rule) {
            $query = preg_replace('/^.+\?/U', '', $rule);
            if ('url' == $mode && $query != $rule) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($_REQUEST, $param);
                $rule      = preg_replace('/\?.*$/U', '', $rule);
                if (in_array($rule, $name) && $intersect == $param) {
                    //如果节点相符且url参数满足
                    $list[] = $rule;
                }
            } else {
                if (in_array($rule, $name)) {
                    $list[] = $rule;
                }
            }
        }
        if ('or' == $relation && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation && empty($diff)) {
            return true;
        }

        return false;
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     * @param $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGroups($uid = null)
    {
        is_null($uid) && $uid = auth()->user()->id;
        static $groups = [];
        if (isset($groups[$uid])) {
            return $groups[$uid];
        }

        // 转换表名
        $auth_group_access = $this->config['auth_group_access'];
        $auth_group        = $this->config['auth_group'];
        // 执行查询
        $user_groups  = Db::view($auth_group_access, 'uid,group_id')
            ->view($auth_group, 'id,name,title,rules', "{$auth_group_access}.group_id={$auth_group}.id", 'LEFT')
            ->where("{$auth_group_access}.uid='{$uid}' and {$auth_group}.status='1'")
            ->select();
        $groups[$uid] = $user_groups ?: [];
        return $groups[$uid];
    }

    public function isRole($role)
    {
        $role  = implode('|', $role);
        $group = $this->getGroups();
        var_dump($group);

    }

    /**
     * 获得权限规则列表
     * @param integer $uid 用户id
     * @return array
     */
    public function getRuleList($uid)
    {
        static $_rulelist = []; //保存用户验证通过的权限列表
        if (isset($_rulelist[$uid])) {
            return $_rulelist[$uid];
        }
        if (2 == $this->config['auth_type'] && session('?_rule_list_' . $uid)) {
            return session('_rule_list_' . $uid);
        }

        // 读取用户规则节点
        $ids = $this->getRuleIds($uid);
        if (empty($ids)) {
            $_rulelist[$uid] = [];
            return [];
        }

        // 筛选条件
        $where = [
            'status' => '1'
        ];

        //循环规则，判断结果。
        $rulelist = []; //
        if (!in_array('*', $ids)) {
            $where['id'] = ['in', $ids];
        } else {
            return ['*'];
        }
        //读取用户组所有权限规则
        $rules = Db::name($this->config['auth_rule'])->where($where)->field('id,condition,name')->select();

        foreach ($rules as $rule) {
            //超级管理员无需验证condition
            if (!empty($rule['condition']) && !in_array('*', $ids)) {
                //根据condition进行验证
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $rulelist[$rule['id']] = strtolower($rule['name']);
                }
            } else {
                //只要存在就记录
                $rulelist[$rule['id']] = strtolower($rule['name']);
            }
        }
        $_rulelist[$uid] = $rulelist;

        //登录验证则需要保存规则列表
        if (2 == $this->config['auth_type']) {
            //规则列表结果保存到session
            session('_rule_list_' . $uid, $rulelist);
        }
        return array_unique($rulelist);
    }

    /**
     * 获取规则ids
     * @param $uid
     * @return array
     */
    public function getRuleIds($uid)
    {
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids    = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        return $ids;
    }

    /**
     * 获取用户菜单
     * @param null $uid
     * @param null $path
     * @return array
     */
    public function getMenu($uid = null, $path = null)
    {
        if (is_null($path)) $path = $this->getPath();
        if (is_null($uid)) $uid = $this->getUserId();

        $self     = Db::table('routes')->where('uri', '=', $path)->get();
        $map      = ['is_menu' => 1];
        $rule_ids = $this->getRuleIds($uid);
        if ($rule_ids && $rule_ids[0] != '*') {
            $map['id'] = ['in', $rule_ids];
        }

        $auth_rule = $this->config['auth_rule'];
        $list      = Cache::remember('sys:cache:menu:' . $uid, function () use ($auth_rule, $uid, $map) {
            return Db::table($auth_rule)->field('id,pid,name,title,icon,sort')->where($map)->order('pid asc,sort asc,id asc')->select();
        });

        $crumb = Cache::remember('sys:cache:crumb:' . $uid, function () use ($auth_rule, $uid, $map) {
            unset($map['is_menu']);
            return Db::table($auth_rule)->field('id,pid,name,title,icon,sort')->where($map)->order('pid asc,sort asc,id asc')->select();
        });

        $crumb      = Tree::getParents($crumb, $self['id']);
        $parent_ids = [$self['id']];

        foreach ($crumb as $val) {
            array_push($parent_ids, $val['id']);
        }

        $menu         = ArrayHelp::list_to_tree($list, 'id', 'pid', 'child');
        $sort_by_sort = function ($x, $y) {
            if ($x['sort'] == $y['sort']) {
                return 0;
            }
            return ($x['sort'] < $y['sort']) ? -1 : 1;
        };
        uasort($menu, $sort_by_sort);

        $data = [
            'menu'  => $menu,
            'crumb' => $crumb,
            'pids'  => $parent_ids,
            'self'  => $self
        ];
        return $data;
    }


}
