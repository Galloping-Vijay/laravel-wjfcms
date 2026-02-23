# CLAUDE.md

本文件为 Claude Code (claude.ai/code) 在此代码仓库中工作时提供指导。

## 项目概述

laravel-wjfcms 是一个基于 Laravel 的内容管理系统,使用 Layui 前端框架,具有 RBAC 权限管理、文章管理和多守卫认证功能。这是一个中文项目(臭大佬/Chou Da Lao)。

## 开发命令

### 初始化设置
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 开发

```bash
# 运行开发服务器
php artisan serve

# 资源编译
npm run dev          # 开发环境构建
npm run watch        # 监听文件变化
npm run production   # 生产环境构建

# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 测试

```bash
# 运行所有测试
vendor/bin/phpunit

# 运行特定测试套件
vendor/bin/phpunit --testsuite=Feature
vendor/bin/phpunit --testsuite=Unit
```

### 数据库

```bash
# 运行迁移
php artisan migrate

# 回滚迁移
php artisan migrate:rollback

# 填充数据库
php artisan db:seed

# 刷新数据库(删除所有表并重新迁移)
php artisan migrate:fresh --seed
```

## 架构

### 多守卫认证系统

应用使用 Laravel 的多守卫认证,包含两个独立的守卫:

- **`web` 守卫**: 用于前台用户(User 模型)
- **`admin` 守卫**: 用于后台管理员(Admin 模型)

关键文件:


- [config/auth.php](config/auth.php) - 守卫配置
- [app/Http/Middleware/AdminLogin.php](app/Http/Middleware/AdminLogin.php) - 重定向已登录的管理员
- 中间件 `admin` - 保护管理员路由(检查 `admin` 守卫)

### RBAC 权限系统

使用 Spatie 的 Laravel Permission 包,采用自定义架构:

- **权限(Permissions)**: 层级菜单结构,包含 `parent_id`、`level`、`display_menu`、`icon`、`url`
- **角色(Roles)**: 分配给管理员,带有状态字段
- **关联表**: `model_has_permissions`、`model_has_roles`、`role_has_permissions`

迁移文件: [database/migrations/2019_05_05_145344_create_permission_tables.php](database/migrations/2019_05_05_145344_create_permission_tables.php)

### TraitResource 模式

控制器使用 `TraitResource` trait 实现标准化的 CRUD 操作:

```php
use TraitResource;

public function __construct()
{
    self::$model = Article::class;
    self::$controlName = 'article';
}
```

该 trait 提供:


- `index()` - 列表页,支持 POST 获取 AJAX 数据
- `create()` - 显示创建表单
- `store()` - 创建记录
- `edit($id)` - 显示编辑表单
- `update()` - 更新记录
- `destroy()` - 软删除
- `restore()` - 恢复软删除的记录
- `forceDelete()` - 永久删除
- `resJson()` - 标准化 JSON 响应

文件: [app/Http/Traits/TraitResource.php](app/Http/Traits/TraitResource.php)

### 路由结构

路由在 [routes/web.php](routes/web.php) 中按命名空间组织:

- **Home 命名空间**: 前台路由(文章、分类、标签、评论、用户资料)
- **Admin 命名空间**: 后台路由,使用 `admin` 中间件
- **Auth 命名空间**: 认证(登录、注册、社交登录、登出)

管理员路由使用 `/admin` 前缀,并受 `admin` 中间件保护。

### 文章管理

文章支持:


- Markdown 内容(存储在 `markdown` 字段,HTML 存储在 `content` 字段)
- 分类关联(属于 Category)
- 标签(以逗号分隔存储在 `keywords` 字段)
- 封面图片及水印
- 状态(0=待审核,1=已发布)
- 置顶文章(`is_top` 字段)
- 点击量跟踪
- 软删除

模型: [app/Models/Article.php](app/Models/Article.php)
控制器: [app/Http/Controllers/Admin/ArticleController.php](app/Http/Controllers/Admin/ArticleController.php)

### 辅助函数

全局辅助函数位于 [app/helpers.php](app/helpers.php):

- `pr($data, $choice)` - 调试打印并退出
- `getRandomStr($len, $special)` - 生成随机字符串
- `waterMarkImage($img, $isCover, $text, $color, $size)` - 为图片添加水印
- `getIp()` - 获取客户端 IP 地址

### 配置

自定义配置位于 [config/vijay.php](config/vijay.php):


- 应用元数据(名称、版本、作者)
- 水印设置(`vijay.water.text`、`vijay.water.color`、`vijay.water.size`)
- 外部链接(文档、GitHub、商店)

### 数据库前缀

数据表使用 `wjf_` 前缀(在 `.env` 中配置为 `DB_PREFIX=wjf_`)。

### 第三方集成

系统集成了:


- 微信(overtrue/laravel-wechat)
- 社交登录(QQ、微博、微信网页版,通过 Socialite)
- 百度(百家号 Baijiahao,用于文章发布)
- 图片处理(Intervention Image)
- 验证码(mews/captcha)

## 关键模式

### 软删除

大多数模型使用软删除。控制器处理三种状态:


- `delete=0`(默认): 仅活动记录
- `delete=1`: 仅已删除记录(`onlyTrashed()`)
- `delete=2`: 所有记录(`withTrashed()`)

### 图片水印

图片使用 `waterMarkImage()` 辅助函数自动添加水印。封面图片会调整为 218x218px。默认图片(包含 'default-img')不添加水印。

### JSON 响应

所有 AJAX 响应使用 `resJson($code, $msg, $data, $additional)`:
```php
return $this->resJson(0, '操作成功', $data, ['count' => $count]);
```

### 视图结构

管理员视图遵循模式: `resources/views/admin/{controller}/{action}.blade.php`

每个视图接收:


- `$control_name` - 控制器名称,用于路由
- `$info` - 记录数据(用于编辑视图)
- 其他上下文(列表、选项等)

## 重要说明

- 全程使用中文(注释、界面、数据库内容)
- 需要数据库填充来初始化权限和角色
- 生产环境可能禁用管理员注册(检查路由)
- 文件上传默认使用 public 磁盘
- 会话使用 file 驱动(生产环境建议使用 Redis)
- 队列连接为 `sync`(生产环境建议使用 Redis/database)
