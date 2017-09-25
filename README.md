## 说明

这是一个引入[jormin/laravel-ddoc](https://github.com/jormin/laravel-ddoc)扩展的`laravel`项目，主要功能是`自动读取数据库信息` 并显示在网页上，支持导出 `Html` 和 `PDF` 文件。

> 1. 导出 `Html` 实际为生成并导出一个离线版本的压缩包。
> 2. 导出 `PDF` 使用了 [laravel-snappy](https://github.com/barryvdh/laravel-snappy)扩展包

## 安装

 1. 克隆项目

	``` bash
	$ git clone https://github.com/jormin/ddoc.git
	```

 2. 安装依赖库

	``` bash
	$ composer install
	```
 
## 配置

1. 创建修改配置文件：

    ``` bash
    $ cd /path/to/your/project
    $ cp .env.example .env
    $ vim .env
      # 修改参数值
      # APP_NAME：项目名称，导出的文件会使用这个配置项
      # DB_CONNECTION：数据库连接，默认`mysql`
      # DB_HOST：数据库HOST，默认`127.0.0.1`
      # DB_PORT：数据库端口，默认`3306`
      # DB_DATABASE：数据库名称
      # DB_USERNAME：账号
      # DB_PASSWORD：密码
      # DB_PREFIX：数据表前缀
    ```
	
2. 修改文档底部文案和链接
	
    ``` bash
    $ cd /path/to/your/project/config/
    $ vim laravel-ddoc.php
    ```
	
3. 配置导出PDF文件参数
	
    ``` bash
    $ cd /path/to/your/project/config/
    $ vim snappy.php
    ```
	    
	    > `pdf.binary` 项配置 `wkhtmltopdf` 执行文件的目录
	    
	    > `linux/unix/mac` 系统的执行文件存放于 `项目目录/vendor/h4cc/wkhtmltopdf-[amd64|i386]/bin/` 目录下
	    
	    > `wundiws` 系统的执行文件存放于 `项目目录/vendor/wemersonjanuario/wkhtmltopdf-windows/bin/[64bit|32bit]/` 目录下

## 使用

配置好后，浏览器访问 `[http|https]://[your domain or ip]/ddoc`

## 参考问题

1. Q：导出的 `PDF` 文件中文不显示或者乱码？
	
	A：导致此问题的原因是机器上没有安装中文字体，解决方式如下
	
	```
	1、先从本机或者网络上下载所需的中文字体
	2、修改字体文件的权限，使root用户以外的用户也可以使用
		$ cd /usr/share/fonts/chinese/
	3、建立字体缓存
		$ sudo mkfontscale
		$ # 如果提示 mkfontscale: command not found，则需要安装# sudo apt-get install ttf-mscorefonts-installer
		$ sudo mkfontdir 
		$ sudo fc-cache -fv
		$ # 如果提示 fc-cache: command not found，则需要安装# sudo apt-get install fontconfig
	```

## 参考图

![](https://qiniu.blog.lerzen.com/8a066a40-161b-11e7-92cc-e978e5791021.jpg)

![](https://qiniu.blog.lerzen.com/95bb00d0-161b-11e7-a852-9fb963e13414.jpg)

![](https://qiniu.blog.lerzen.com/a2d1f730-161b-11e7-a10b-458e1139cb1a.jpg)

![](https://qiniu.blog.lerzen.com/cd6439d0-161b-11e7-83ae-01bf49de6b3e.jpg)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
