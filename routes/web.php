<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('test', function () {
    pr(2);
});

Auth::routes();

// Home 模块
Route::namespace('Home')->group(function () {
    // 首页
    Route::get('/', 'IndexController@index');
    // 分类
    Route::get('category/{id}', 'IndexController@category');
    // 标签
    Route::get('tag/{id}', 'IndexController@tag');
    // 随言碎语
    Route::get('chat', 'IndexController@chat');
    // 开源项目
    Route::get('git', 'IndexController@git');
    // 文章详情
    Route::get('article/{id}', 'IndexController@article');
    // 文章评论
    Route::post('comment', 'IndexController@comment')->middleware('auth.oauth');
    // 检测是否登录
    Route::get('checkLogin', 'IndexController@checkLogin');
    // 搜索文章
    Route::post('search', 'IndexController@search');
    // feed
    Route::get('feed', 'IndexController@feed');
    // 推荐博客
    Route::prefix('site')->group(function () {
        Route::get('/', 'SiteController@index');
        Route::post('store', 'SiteController@store')->middleware('auth.oauth', 'clean.xss');
    });
});

// auth
Route::namespace('Auth')->prefix('auth')->group(function () {
    // 第三方登录
    Route::prefix('oauth')->group(function () {
        // 重定向
        Route::get('redirectToProvider/{service}', 'OAuthController@redirectToProvider');
        // 获取用户资料并登录
        Route::get('handleProviderCallback/{service}', 'OAuthController@handleProviderCallback');
        // 退出登录
        Route::get('logout', 'OAuthController@logout');
    });

    // 后台登录
    Route::prefix('admin')->group(function () {
        Route::post('login', 'AdminController@login');
    });
});

// 后台登录页面
Route::namespace('Admin')->prefix('admin')->group(function () {
    // 登录页面
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@ajaxLogin');
    // 退出
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
    //注册
    Route::get('register', 'RegisterController@showRegistrationForm')->name('admin.register');
    Route::post('register', 'RegisterController@register');
});


// Admin 模块
Route::namespace('Admin')->middleware('admin')->prefix('admin')->group(function () {
    // 首页控制器
    Route::get('/', 'IndexController@index')->name('admin.index');
    //测试
    Route::prefix('test')->group(function () {
        Route::get('index', 'TestController@index');
        Route::get('/', 'TestController@index');
        Route::get('cc', 'TestController@cc');
    });
    //首页管理
    Route::prefix('index')->group(function () {
        // 后台首页
        Route::get('index', 'IndexController@index');
        Route::get('main', 'IndexController@main');
        // 更新系统
        Route::get('upgrade', 'IndexController@upgrade');
    });

    //管理员管理
    Route::prefix('admin')->group(function () {
        // 列表
        Route::any('index', 'AdminController@index');
        // 创建
        Route::get('create', 'AdminController@create');
        Route::post('store', 'AdminController@store');
        // 展示
        Route::get('show/{id}', 'AdminController@show');
        // 编辑
        Route::get('edit/{id}', 'AdminController@edit');
        // 更新
        Route::post('update', 'AdminController@update');
        // 删除
        Route::post('destroy', 'AdminController@destroy');
        // 恢复删除
        Route::post('restore', 'AdminController@restore');
        // 彻底删除
        Route::post('forceDelete', 'AdminController@forceDelete');
    });

    //权限管理
    Route::prefix('permission')->group(function () {
        // 列表
        Route::any('index', 'PermissionController@index');
        // 创建
        Route::get('create', 'PermissionController@create');
        Route::post('store', 'PermissionController@store');
        // 展示
        Route::get('show/{id}', 'PermissionController@show');
        // 编辑
        Route::get('edit/{id}', 'PermissionController@edit');
        // 更新
        Route::post('update', 'PermissionController@update');
        // 删除
        Route::post('destroy', 'PermissionController@destroy');
        // 恢复删除
        Route::post('restore', 'PermissionController@restore');
        // 彻底删除
        Route::post('forceDelete', 'PermissionController@forceDelete');
        //获取菜单树
        Route::post('menu', 'PermissionController@menu');
        //获取权限树
        Route::any('permissionTree', 'PermissionController@permissionTree');
    });

    //角色管理
    Route::prefix('role')->group(function () {
        // 列表
        Route::any('index', 'RoleController@index');
        // 创建
        Route::get('create', 'RoleController@create');
        Route::post('store', 'RoleController@store');
        // 展示
        Route::get('show/{id}', 'RoleController@show');
        // 编辑
        Route::get('edit/{id}', 'RoleController@edit');
        // 更新
        Route::post('update', 'RoleController@update');
        // 删除
        Route::post('destroy', 'RoleController@destroy');
        // 恢复删除
        Route::post('restore', 'RoleController@restore');
        // 彻底删除
        Route::post('forceDelete', 'RoleController@forceDelete');
    });

    // 系统设置
    Route::prefix('systemConfig')->group(function () {
        //基础设置
        Route::any('basal', 'SystemConfigController@basal');
    });

    // 文章管理
    Route::prefix('article')->group(function () {
        // 文章列表
        Route::get('index', 'ArticleController@index');
        // 发布文章
        Route::get('create', 'ArticleController@create');
        Route::post('store', 'ArticleController@store');
        // 编辑文章
        Route::get('edit/{id}', 'ArticleController@edit');
        Route::post('update/{id}', 'ArticleController@update');
        // 上传图片
        Route::post('uploadImage', 'ArticleController@uploadImage');
        // 删除文章
        Route::get('destroy/{id}', 'ArticleController@destroy');
        // 恢复删除的文章
        Route::get('restore/{id}', 'ArticleController@restore');
        // 彻底删除文章
        Route::get('forceDelete/{id}', 'ArticleController@forceDelete');
        // 批量替换功能视图
        Route::get('replaceView', 'ArticleController@replaceView');
        // 批量替换功能
        Route::post('replace', 'ArticleController@replace');
    });

    // 分类管理
    Route::prefix('category')->group(function () {
        // 分类列表
        Route::get('index', 'CategoryController@index');
        // 添加分类
        Route::get('create', 'CategoryController@create');
        Route::post('store', 'CategoryController@store');
        // 编辑分类
        Route::get('edit/{id}', 'CategoryController@edit');
        Route::post('update/{id}', 'CategoryController@update');
        // 排序
        Route::post('sort', 'CategoryController@sort');
        // 删除分类
        Route::get('destroy/{id}', 'CategoryController@destroy');
        // 恢复删除的分类
        Route::get('restore/{id}', 'CategoryController@restore');
        // 彻底删除分类
        Route::get('forceDelete/{id}', 'CategoryController@forceDelete');
    });

    // 标签管理
    Route::prefix('tag')->group(function () {
        // 标签列表
        Route::get('index', 'TagController@index');
        // 添加标签
        Route::get('create', 'TagController@create');
        Route::post('store', 'TagController@store');
        // 编辑标签
        Route::get('edit/{id}', 'TagController@edit');
        Route::post('update/{id}', 'TagController@update');
        // 删除标签
        Route::get('destroy/{id}', 'TagController@destroy');
        // 恢复删除的标签
        Route::get('restore/{id}', 'TagController@restore');
        // 彻底删除标签
        Route::get('forceDelete/{id}', 'TagController@forceDelete');
    });

    // 评论管理
    Route::prefix('comment')->group(function () {
        // 评论列表
        Route::get('index', 'CommentController@index');
        // 删除评论
        Route::get('destroy/{id}', 'CommentController@destroy');
        // 恢复删除的评论
        Route::get('restore/{id}', 'CommentController@restore');
        // 彻底删除评论
        Route::get('forceDelete/{id}', 'CommentController@forceDelete');
        // 批量替换功能视图
        Route::get('replaceView', 'CommentController@replaceView');
        // 批量替换功能
        Route::post('replace', 'CommentController@replace');
    });

    // 第三方用户管理
    Route::prefix('oauthUser')->group(function () {
        // 用户列表
        Route::get('index', 'OauthUserController@index');
        // 编辑管理员
        Route::get('edit/{id}', 'OauthUserController@edit');
        Route::post('update/{id}', 'OauthUserController@update');
    });

    // 友情链接管理
    Route::prefix('friendshipLink')->group(function () {
        // 友情链接列表
        Route::get('index', 'FriendshipLinkController@index');
        // 添加友情链接
        Route::get('create', 'FriendshipLinkController@create');
        Route::post('store', 'FriendshipLinkController@store');
        // 编辑友情链接
        Route::get('edit/{id}', 'FriendshipLinkController@edit');
        Route::post('update/{id}', 'FriendshipLinkController@update');
        // 排序
        Route::post('sort', 'FriendshipLinkController@sort');
        // 删除友情链接
        Route::get('destroy/{id}', 'FriendshipLinkController@destroy');
        // 恢复删除的友情链接
        Route::get('restore/{id}', 'FriendshipLinkController@restore');
        // 彻底删除友情链接
        Route::get('forceDelete/{id}', 'FriendshipLinkController@forceDelete');
    });

    // 推荐博客管理
    Route::prefix('site')->group(function () {
        // 推荐博客列表
        Route::get('index', 'SiteController@index');
        // 添加推荐博客
        Route::get('create', 'SiteController@create');
        Route::post('store', 'SiteController@store');
        // 编辑推荐博客
        Route::get('edit/{id}', 'SiteController@edit');
        Route::post('update/{id}', 'SiteController@update');
        // 排序
        Route::post('sort', 'SiteController@sort');
        // 删除推荐博客
        Route::get('destroy/{id}', 'SiteController@destroy');
        // 恢复删除的推荐博客
        Route::get('restore/{id}', 'SiteController@restore');
        // 彻底删除推荐博客
        Route::get('forceDelete/{id}', 'SiteController@forceDelete');
    });

    // 随言碎语管理
    Route::prefix('chat')->group(function () {
        // 随言碎语列表
        Route::get('index', 'ChatController@index');
        // 添加随言碎语
        Route::get('create', 'ChatController@create');
        Route::post('store', 'ChatController@store');
        // 编辑随言碎语
        Route::get('edit/{id}', 'ChatController@edit');
        Route::post('update/{id}', 'ChatController@update');
        // 删除随言碎语
        Route::get('destroy/{id}', 'ChatController@destroy');
        // 恢复删除的随言碎语
        Route::get('restore/{id}', 'ChatController@restore');
        // 彻底删除随言碎语
        Route::get('forceDelete/{id}', 'ChatController@forceDelete');
    });

    // 开源项目管理
    Route::prefix('gitProject')->group(function () {
        // 开源项目列表
        Route::get('index', 'GitProjectController@index');
        // 添加开源项目
        Route::get('create', 'GitProjectController@create');
        Route::post('store', 'GitProjectController@store');
        // 编辑开源项目
        Route::get('edit/{id}', 'GitProjectController@edit');
        Route::post('update/{id}', 'GitProjectController@update');
        // 排序
        Route::post('sort', 'GitProjectController@sort');
        // 删除开源项目
        Route::get('destroy/{id}', 'GitProjectController@destroy');
        // 恢复删除的开源项目
        Route::get('restore/{id}', 'GitProjectController@restore');
        // 彻底删除开源项目
        Route::get('forceDelete/{id}', 'GitProjectController@forceDelete');
    });

    // 菜单管理
    Route::prefix('nav')->group(function () {
        // 菜单列表
        Route::get('index', 'NavController@index');
        // 添加菜单
        Route::get('create', 'NavController@create');
        Route::post('store', 'NavController@store');
        // 编辑菜单
        Route::get('edit/{id}', 'NavController@edit');
        Route::post('update/{id}', 'NavController@update');
        // 排序
        Route::post('sort', 'NavController@sort');
        // 删除菜单
        Route::get('destroy/{id}', 'NavController@destroy');
        // 恢复删除的菜单
        Route::get('restore/{id}', 'NavController@restore');
        // 彻底删除菜单
        Route::get('forceDelete/{id}', 'NavController@forceDelete');
    });
});

