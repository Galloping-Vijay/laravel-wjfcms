---

---

伪静态

 

 做完以上工作后,访问站点还存在问题,还需要配置伪静态,把站点运行目录指向public

![](./images/screenshot_1565621361361.png)

## 以宝塔为例

进入宝塔面板->站点,点击相应的站点

![screenshot_1565621661775](./images/screenshot_1565621661775.png)

win下没有laravel5,选择thinkphp

![](./images/screenshot_1565621745758.png)

 

# linux权限问题

在安装 Laravel 后，你可能需要配置一些权限。`storage`和`bootstrap/cache` 、`public`目录在你的 web 服务下应该是可写的权限，否则 Laravel 将无法运行。

报错如下:

![](./images/screenshot_1565621987669.png)

 

运行如下命令

`sudo chmod -R 777 vendor storage bootstrap/cache`