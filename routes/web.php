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
    $link = \App\Models\FriendLink::query()->find(4);
    \App\Jobs\TestJob::dispatch($link);
});

//错误页
Route::get('blank', 'Home\BlankController@index')->name('blank');

//用户登录注册
Auth::routes();
Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::post('ajaxLogin', 'LoginController@ajaxLogin');
    // 退出登录
    Route::get('logout', 'AuthenticationController@logout');
    // 第三方登录
    Route::get('/{social}', 'AuthenticationController@getSocialRedirect')
        ->middleware('guest');
    Route::get('/{social}/callback', 'AuthenticationController@getSocialCallback')
        ->middleware('guest');
    // 后台登录
    Route::prefix('admin')->group(function () {
        Route::post('login', 'AdminController@login');
    });
});

// Home 模块
Route::namespace('Home')->group(function () {
    // 首页
    Route::any('/', 'IndexController@index');
    // 分类
    Route::get('category/{category}', 'IndexController@category');
    // 标签
    Route::get('tag/{tag}', 'IndexController@tag');
    // 归档文章
    Route::get('archive', 'IndexController@archive');
    // 有些话
    Route::get('chat', 'IndexController@chat');
    // 文章详情
    Route::get('article/{id}', 'IndexController@article');
    // 搜索文章
    Route::post('search', 'IndexController@search');
    //申请友情链接
    Route::post('applyLink', 'IndexController@applyLink');
    //获取评论
    Route::any('ajaxComment', 'IndexController@ajaxComment');
    //历史上的今天
    Route::any('history', 'IndexController@history');
    //热度榜
    Route::any('clickArticle', 'IndexController@clickArticle');
    //友情链接
    Route::any('friendLinks', 'IndexController@friendLinks');
    //标签云
    Route::any('ajaxTags', 'IndexController@ajaxTags');

    //用户
    Route::prefix('user')->middleware('auth:web')->group(function () {
        //个人中心
        Route::get('/', 'UserController@index');
        // 上传图片
        Route::post('uploadImage', 'UserController@uploadImage');
        //编辑(修改头像)
        Route::any('modify', 'UserController@modify');
        Route::post('update', 'UserController@update');
        //文章评论
        Route::post('comment', 'UserController@comment');
        Route::post('commentAction', 'UserController@commentAction');
    });

    // 工具类
    Route::prefix('tools')->group(function () {
        // 百度自动提交
        Route::get('linkSubmit', 'ToolsController@linkSubmit');
        Route::get('tuling', 'ToolsController@tuling');
        //发送邮件
        Route::post('getEmailCode', 'ToolsController@getEmailCode');
    });
    //微信
    Route::prefix('wechat')->group(function () {
        Route::any('/', 'WeChatController@serve');
    });
    //百度
    Route::prefix('baidu')->group(function () {
        Route::any('serve', 'BaiduController@serve');
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
        Route::get('info', 'AdminController@info');
        Route::any('password', 'AdminController@password');
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

    //用户管理
    Route::prefix('user')->group(function () {
        // 列表
        Route::any('index', 'UserController@index');
        // 创建
        Route::get('create', 'UserController@create');
        Route::post('store', 'UserController@store');
        // 展示
        Route::get('show/{id}', 'UserController@show');
        // 编辑
        Route::get('edit/{id}', 'UserController@edit');
        // 更新
        Route::post('update', 'UserController@update');
        // 删除
        Route::post('destroy', 'UserController@destroy');
        // 恢复删除
        Route::post('restore', 'UserController@restore');
        // 彻底删除
        Route::post('forceDelete', 'UserController@forceDelete');
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
        // 列表
        Route::any('index', 'ArticleController@index');
        // 创建
        Route::get('create', 'ArticleController@create');
        Route::post('store', 'ArticleController@store');
        // 展示
        Route::get('show/{id}', 'ArticleController@show');
        // 编辑
        Route::get('edit/{id}', 'ArticleController@edit');
        // 更新
        Route::post('update', 'ArticleController@update');
        // 删除
        Route::post('destroy', 'ArticleController@destroy');
        // 恢复删除
        Route::post('restore', 'ArticleController@restore');
        // 彻底删除
        Route::post('forceDelete', 'ArticleController@forceDelete');
        // 上传图片
        Route::post('uploadImage', 'ArticleController@uploadImage');
        // 批量替换功能视图
        Route::get('replaceView', 'ArticleController@replaceView');
        // 批量替换功能
        Route::post('replace', 'ArticleController@replace');
    });

    // 分类管理
    Route::prefix('category')->group(function () {
        // 列表
        Route::any('index', 'CategoryController@index');
        // 创建
        Route::get('create', 'CategoryController@create');
        Route::post('store', 'CategoryController@store');
        // 展示
        Route::get('show/{id}', 'CategoryController@show');
        // 编辑
        Route::get('edit/{id}', 'CategoryController@edit');
        // 更新
        Route::post('update', 'CategoryController@update');
        // 删除
        Route::post('destroy', 'CategoryController@destroy');
        // 恢复删除
        Route::post('restore', 'CategoryController@restore');
        // 彻底删除
        Route::post('forceDelete', 'CategoryController@forceDelete');
    });

    // 评论管理
    Route::prefix('comment')->group(function () {
        // 评论列表
        Route::any('index', 'CommentController@index');
        // 更新
        Route::post('update', 'CommentController@update');
        // 删除
        Route::post('destroy', 'CommentController@destroy');
        // 恢复删除
        Route::post('restore', 'CommentController@restore');
        // 彻底删除
        Route::post('forceDelete', 'CommentController@forceDelete');
        // 批量替换功能
        Route::any('replace', 'CommentController@replace');
    });

    // 有些话管理
    Route::prefix('chat')->group(function () {
        // 列表
        Route::any('index', 'ChatController@index');
        // 创建
        Route::get('create', 'ChatController@create');
        Route::post('store', 'ChatController@store');
        // 展示
        Route::get('show/{id}', 'ChatController@show');
        // 编辑
        Route::get('edit/{id}', 'ChatController@edit');
        // 更新
        Route::post('update', 'ChatController@update');
        // 删除
        Route::post('destroy', 'ChatController@destroy');
        // 恢复删除
        Route::post('restore', 'ChatController@restore');
        // 彻底删除
        Route::post('forceDelete', 'ChatController@forceDelete');
    });

    // 标签管理
    Route::prefix('tag')->group(function () {
        // 列表
        Route::any('index', 'TagController@index');
        // 创建
        Route::get('create', 'TagController@create');
        Route::post('store', 'TagController@store');
        // 展示
        Route::get('show/{id}', 'TagController@show');
        // 编辑
        Route::get('edit/{id}', 'TagController@edit');
        // 更新
        Route::post('update', 'TagController@update');
        // 删除
        Route::post('destroy', 'TagController@destroy');
        // 恢复删除
        Route::post('restore', 'TagController@restore');
        // 彻底删除
        Route::post('forceDelete', 'TagController@forceDelete');
    });

    //前台导航菜单
    Route::prefix('nav')->group(function () {
        // 列表
        Route::any('index', 'NavController@index');
        // 创建
        Route::get('create', 'NavController@create');
        Route::post('store', 'NavController@store');
        // 展示
        Route::get('show/{id}', 'NavController@show');
        // 编辑
        Route::get('edit/{id}', 'NavController@edit');
        // 更新
        Route::post('update', 'NavController@update');
        // 删除
        Route::post('destroy', 'NavController@destroy');
        // 恢复删除
        Route::post('restore', 'NavController@restore');
        // 彻底删除
        Route::post('forceDelete', 'NavController@forceDelete');
    });

    // 友情链接管理
    Route::prefix('friendLinks')->group(function () {
        // 列表
        Route::any('index', 'FriendLinksController@index');
        // 创建
        Route::get('create', 'FriendLinksController@create');
        Route::post('store', 'FriendLinksController@store');
        // 展示
        Route::get('show/{id}', 'FriendLinksController@show');
        // 编辑
        Route::get('edit/{id}', 'FriendLinksController@edit');
        // 更新
        Route::post('update', 'FriendLinksController@update');
        // 删除
        Route::post('destroy', 'FriendLinksController@destroy');
        // 恢复删除
        Route::post('restore', 'FriendLinksController@restore');
        // 彻底删除
        Route::post('forceDelete', 'FriendLinksController@forceDelete');
    });

    //百家号
    Route::prefix('baijiahao')->group(function () {
        // 文章操作
        Route::prefix('article')->group(function () {
            Route::any('publish', 'Baijiahao\ArticleController@publish');
        });
    });

    // 微信
    Route::prefix('weChat')->group(function () {
        // 关键字操作
        Route::prefix('keyword')->group(function () {
            Route::any('index', 'Wechat\KeywordController@index');
            Route::get('create', 'Wechat\KeywordController@create');
            Route::post('store', 'Wechat\KeywordController@store');
            Route::get('show/{id}', 'Wechat\KeywordController@show');
            Route::get('edit/{id}', 'Wechat\KeywordController@edit');
            Route::post('update', 'Wechat\KeywordController@Update');
            Route::post('destroy', 'Wechat\KeywordController@destroy');
        });
    });

});

