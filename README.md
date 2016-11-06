Laravel 5  UEditor
=============

[UEditor](http://ueditor.baidu.com) 是由百度web前端研发部开发所见即所得富文本web编辑器

此包为laravel5的支持,新增多语言配置,可自由部署前端代码,默认基于 UEditor 1.4.3.3

UEditor 前台文件完全无修改,可自由gulp等工具部署到生产环境
 
根据系统的config.app.locale自动切换多语言. 暂时只支持 en,zh_CN,zh_TW

支持本地和七牛云存储,默认为本地上传 public/uploads

##ChangeLog
 1.4.0 版  支持 laravel5.3 更新百度 UEditor 1.4.3.3

 1.3.0 版  改变服务器请求路由 为 /laravel-u-editor-server/server 
           老版本升级,需要 更改 public/ueditor.config.js 
          
            , serverUrl: "/laravel-u-editor-server/server"

 1.2.5 版 增加对Laravel5.* 的支持,更新百度 UEditor 1.4.3.1
 
 1.2 版 增加对Laravel5.1 的支持,修改一些说明
 
 1.1 版 增加七牛云存储的支持

## 重要提示
有些同学配置总是不成功,除了一般设置,权限等基础问题,很大的可能是 middleware和 csrf 没配置好.
因为这两点对于服务器的安全至关重要,因此都是必须配置正确的,否则无法运行.
如何配置需要一定基础,对于看完且理解L5官方文档的同学,应该都有此基础.




## Installation

[PHP](https://php.net) 5.4+ , and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Exceptions, simply add the following line to the require block of your `composer.json` file:

```
"stevenyangecho/laravel-u-editor": "~1.4"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Exceptions is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'Stevenyangecho\UEditor\UEditorServiceProvider'`

then run 

* `php artisan vendor:publish`



## 配置

 若以上安装没问题,自定义项目配置文件会在 config/laravel-u-editor.php  (会自动生成)

        'core' => [
            'route' => [
                'middleware' => 'auth',
            ],
        ],
  middleware 相当重要,请根据自己的项目设置,比如如果在后台使用,请设置为后台的auth middleware.
  如果是单纯本机测试,请将 
  `// 'middleware' => 'auth',` 直接注释掉,如果留 `'middleware'=>''`空值,会产生bug,原因不详.
 
 所有UEditor 的官方资源,会放在 public/laravel-u-editor/ ,可以根据自己的需求,更改.


## Usage

in  your \<head>  block just put 

    @include('UEditor::head');
    
   it will require  assets.
   
   if need,u can change the resources\views\vendor\UEditor\head.blade.php
    to fit your customization .
    
   ok,all done.just use the UEditor.
   
   

    <!-- 加载编辑器的容器 -->
    <script id="container" name="content" type="text/plain">
        这里写你的初始化内容
    </script>

    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
            ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.    
        });
    </script>






The detail useage Please see [http://ueditor.baidu.com](http://ueditor.baidu.com) 

## TODO

1. 跨域上传

 
## License

Laravel 5  UEditor is licensed under [The MIT License (MIT)](LICENSE).
