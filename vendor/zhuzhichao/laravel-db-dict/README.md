Laravel 5 DB Dict
======================

Version: 0.1.7
----
数据字典 for laravel。

团队中经常要沟通表和字段, 免不了写文档, 但是文档永远不够及时和准确, 这样就会给团队带来很多的不必要但是也不可避免的麻烦。

虽然有一部分在线的数据字典（但是很少很少的选择）,还是需要大家在更新数据库之后手动更新。

所以这个 Laravel 扩展应运而生! 能够做到实时的、自动的、准确的、优雅的数据字典。

##目前进度

1. 控制器、模型、迁移文件、命令行工具
2. 将项目的数据表和字段信息同步到字典
3. 字典的路由和列表页面
4. 正常的显示所有表和字段
1. 表和字段的描述更新
3. 列表页面布局优化
4. 页面添加同步字段按钮

##Todos

5. 提供显示删除的字段和表
6. 提供英文版本和多语言支持
7. 表分类显示

## Demo

![laravel-db-dict-demo](http://7xkxib.com1.z0.glb.clouddn.com/laravel-db-dict-demo-2.png)

## Installation

### 安装
composer require zhuzhichao/laravel-db-dict

### 配置
add follow line into config/app.php>providers>array

```
Zhuzhichao\LaravelDbDict\LavavelDbDictProvider::class
```

php artisan vendor:publish

php artisan migrate

### 同步数据字典
php artisan db:dict-sync

## Usage
open Chrome and tap http://yourhost/laravel-db-dict