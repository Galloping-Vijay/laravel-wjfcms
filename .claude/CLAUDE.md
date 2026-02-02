# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

laravel-wjfcms 是基于 Laravel 6.0 和 Layui 开发的后台内容管理系统（CMS），主要用于博客文章管理、用户管理和权限控制。

- **技术栈**: Laravel 6.0 (PHP 7.1.3+), MySQL, Redis, Layui (前端UI)
- **核心功能**: 文章管理、分类标签、评论系统、用户认证、RBAC权限管理、第三方登录、微信公众号集成
- **数据库表前缀**: `wjf_` (可在 `.env` 中配置 `DB_PREFIX`)
- **双后台系统**:
  - 前台用户系统 (Guard: `web`)
  - 后台管理系统 (Guard: `admin`)

## 常用开发命令

### 项目安装与配置
```bash
# 安装依赖
composer install
npm install

# 环境配置
cp .env.example .env
php artisan key:generate

# 数据库迁移
php artisan migrate
php artisan db:seed

# 安装 Laravel Passport (API认证)
php artisan passport:install
```

### 开发服务器
```bash
# 启动开发服务器
php artisan serve

# 前端资源编译
npm run dev          # 开发环境
npm run watch        # 监听文件变化
npm run production   # 生产环境构建
```

### 测试
```bash
# 运行所有测试
php artisan test
# 或
vendor/bin/phpunit

# 运行特定测试套件
vendor/bin/phpunit --testsuite Unit
vendor/bin/phpunit --testsuite Feature

# 运行单个测试文件
vendor/bin/phpunit tests/Feature/ExampleTest.php
```

### 队列和缓存
```bash
# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 队列任务
php artisan queue:work      # 启动队列工作进程
php artisan queue:listen    # 监听队列
```

### 数据库操作
```bash
# 创建迁移文件
php artisan make:migration create_table_name

# 回滚迁移
php artisan migrate:rollback

# 刷新数据库
php artisan migrate:fresh --seed
```

### 代码生成
```bash
# 创建控制器
php artisan make:controller Admin/ExampleController

# 创建模型
php artisan make:model Models/Example -m  # 同时创建迁移文件

# 创建观察者
php artisan make:observer ExampleObserver --model=Models/Example

# 创建中间件
php artisan make:middleware ExampleMiddleware
```

## 架构说明

### 认证系统架构

项目实现了**双 Guard 认证系统**:

1. **前台用户认证** (`auth:web`)
   - 模型: `App\Models\User`
   - 路由前缀: `/user`
   - 登录页面: `/login`
   - 支持第三方登录 (QQ、微博、微信)
   - 使用 Laravel Socialite 和 JWT

2. **后台管理员认证** (`auth:admin`)
   - 模型: `App\Models\Admin`
   - 路由前缀: `/admin`
   - 登录页面: `/admin/login`
   - 中间件: `admin` (定义在 `app/Http/Kernel.php:45-47`)
   - 自定义中间件 `AdminLogin` 用于登录页面跳转

配置文件位置: [config/auth.php](config/auth.php)

### 权限管理架构

使用 [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) 实现 RBAC 权限管理:

- **配置文件**: [config/permission.php](config/permission.php)
- **模型关系**:
  - `App\Models\Admin` - 管理员模型 (使用 `HasRoles` trait)
  - `App\Models\Permissions` - 权限模型
  - `App\Models\Role` - 角色模型
- **核心控制器**:
  - [app/Http/Controllers/Admin/PermissionController.php](app/Http/Controllers/Admin/PermissionController.php) - 权限管理
  - [app/Http/Controllers/Admin/RoleController.php](app/Http/Controllers/Admin/RoleController.php) - 角色管理

权限树形结构通过 `PermissionController::permissionTree()` 方法获取。

### 模型观察者模式

项目使用 Eloquent 观察者监听模型事件:

- **观察者目录**: `app/Observers/`
- **基础观察者**: `BaseObserver` - 所有观察者的父类
- **主要观察者**:
  - `ArticleObserver` - 文章缓存管理、置顶文章处理
  - `CategoryObserver` - 分类变更处理
  - `CommentObserver` - 评论相关逻辑
  - `NavObserver` - 导航菜单处理
  - `SystemConfigObserver` - 系统配置缓存

观察者需要在 `EventServiceProvider` 中注册。

### 路由结构

- **前台路由** (`routes/web.php`):
  - 首页、文章详情、分类、标签、搜索等
  - 命名空间: `App\Http\Controllers\Home`
  - 用户中心路由使用 `auth:web` 中间件

- **后台路由** (`routes/web.php:116-400`):
  - 路由前缀: `/admin`
  - 命名空间: `App\Http\Controllers\Admin`
  - 中间件组: `admin` (包含 `auth:admin`)
  - 资源型路由包含软删除支持 (restore/forceDelete)

- **API路由** (`routes/api.php`):
  - 路由前缀: `/api`
  - 主要用于 OAuth 和第三方集成

### 辅助函数

全局辅助函数定义在 [app/helpers.php](app/helpers.php):

- `pr($data, $choice)` - 调试打印函数
- `getRandomStr($len, $special)` - 生成随机字符串
- `waterMarkImage($img, $isCover, $text, $color, $size)` - 图片水印添加
- `getIp()` - 获取客户端真实IP

该文件在 `composer.json` 中自动加载。

### 第三方集成

- **七牛云存储**: `qiniu/php-sdk`
- **微信公众号**: `overtrue/laravel-wechat`
- **验证码**: `mews/captcha`
- **第三方登录**:
  - `overtrue/socialite`
  - `socialiteproviders/qq`, `socialiteproviders/weibo`, `socialiteproviders/weixin-web`
- **图片处理**: `intervention/image`

第三方登录流程:
1. 访问 `/auth/{social}` 跳转到第三方授权页
2. 回调 `/auth/{social}/callback` 处理授权结果
3. 控制器: `Auth\AuthenticationController`

### 自定义配置

项目自定义配置文件:
- [config/vijay.php](config/vijay.php) - 网站基础信息、版本号、水印配置等
- [config/wechat.php](config/wechat.php) - 微信公众号配置
- [config/jwt.php](config/jwt.php) - JWT 认证配置
- [config/captcha.php](config/captcha.php) - 验证码配置

### 视图结构

- **前台视图**: `resources/views/home/`
- **后台视图**: `resources/views/admin/`
- **布局文件**: `resources/views/layouts/`
- **前端资源**: `public/` (Layui 前端框架)

## 开发注意事项

### 数据库相关
- 数据表前缀默认为 `wjf_`，在 `.env` 中配置
- 默认字符串长度限制为 100 (在 `AppServiceProvider::boot()` 中设置)
- 使用 Eloquent 软删除 (`SoftDeletes` trait) 处理数据删除

### 认证相关
- 添加新的后台功能时，路由必须在 `admin` 中间件组内
- 前台用户功能使用 `auth:web` 中间件
- 权限检查使用 Spatie Permission 包的方法 (`$user->hasPermissionTo()`, `$user->hasRole()`)

### 中间件配置
后台中间件组定义在 [app/Http/Kernel.php](app/Http/Kernel.php#L45-L47):
```php
'admin' => [
    'auth:admin',
]
```

### 环境变量
关键环境变量 (参考 `.env.example`):
- 数据库配置: `DB_*` 和 `DB_PREFIX`
- 第三方服务: 百家号、图灵机器人、微信、QQ AI 等
- 百度站长 API: `BAIDU_SITE_BASE`, `BAIDU_SITE_API`

### 队列任务
- 测试任务示例: `App\Jobs\TestJob`
- 队列配置在 `.env` 中设置 `QUEUE_CONNECTION`
- 建议生产环境使用 Redis 队列

## 文档资源

- 官方文档: https://www.choudalao.com/static/docs/_book/
- GitHub 仓库: https://github.com/Galloping-Vijay/laravel-wjfcms
- 演示地址: http://demo.choudalao.com/admin (账号: 13000000000, 密码: 123456)
