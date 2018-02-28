<?php
/**
 * 后台管理
 */


//登录,退出
Route::any('login', 'PublicController@login')->name('login');
Route::any('logout', function () {
    auth()->logout();
    return redirect()->route('logout');
})->name('logout');

/**
 * 首页，控制台
 */
Route::any('/', 'IndexController@index')->name('/');
Route::any('flexible', 'IndexController@flexible')->name('flexible');

/**
 * 个人中心
 */
Route::any('/me', 'AdminController@me')->name('me');


/**
 * 文件上传
 */
Route::post('/upload/{type}', 'FileController@upload')->name('upload');

/**
 * Rbac权限管理(用户管理)
 */
Route::any('rbac', 'AdminController@index')->name('rbac');
Route::any('rbac/admin', 'AdminController@index')->name('admin-users');
Route::any('rbac/admin/add', 'AdminController@add')->name('add-admin');
Route::any('rbac/admin/edit/{id}', 'AdminController@edit')->name('edit-admin');
Route::any('rbac/admin/assign/{id}', 'AdminController@assign')->name('assign-admin');
Route::get('rbac/admin/delete/{id}', 'AdminController@delete')->name('delete-admin');
Route::get('rbac/admin/status/{id}', 'AdminController@status')->name('status-admin');

/**
 * Rbac权限管理(角色管理)
 */
Route::any('rbac/roles', 'RoleController@index')->name('roles');
Route::any('rbac/role/add', 'RoleController@add')->name('add-role');
Route::any('rbac/role/edit/{id}', 'RoleController@edit')->name('edit-role');
Route::any('rbac/role/assign/{id}', 'RoleController@assign')->name('assign-permission');
Route::get('rbac/role/delete/{id}', 'RoleController@delete')->name('delete-role');

/**
 * Rbac权限管理(权限规则管理)
 */
Route::any('rbac/permission', 'PermissionController@index')->name('permission');
Route::any('rbac/permission/add', 'PermissionController@add')->name('add-permission');
Route::any('rbac/permission/edit/{id}', 'PermissionController@edit')->name('edit-permission');
Route::get('rbac/permission/menu/{id}', 'PermissionController@menu')->name('menu-permission');
Route::get('rbac/permission/sort/{id}', 'PermissionController@sort')->name('sort-permission');
Route::get('rbac/permission/delete/{id}', 'PermissionController@delete')->name('delete-permission');

/**
 * 用户管理
 */
Route::match(['get', 'post'], 'users', 'UserController@index')->name('users');

