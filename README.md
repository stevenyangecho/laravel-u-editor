Laravel 5  UEditor
=============

[UEditor](http://ueditor.baidu.com) 是由百度web前端研发部开发所见即所得富文本web编辑器

此包为laravel5的支持,新增多语言配置,可自由部署前端代码,默认基于 UEditor 1.4.3.

UEditor 前台文件完全无修改,可自由gulp等工具部署到生产环境
 
根据系统的config.app.locale自动切换多语言. 暂时只支持 en,zh_CN,zh_TW

支持本地和七牛云存储,默认为本地上传 public/uploads

##ChangeLog
 1.1 版 增加七牛云存储的支持

## Installation

[PHP](https://php.net) 5.4+ , and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Exceptions, simply add the following line to the require block of your `composer.json` file:

```
"stevenyangecho/laravel-u-editor": "~1.1"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Exceptions is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'Stevenyangecho\UEditor\UEditorServiceProvider'`

then run 

* `php artisan vendor:publish`



## Configuration

 in config/laravel-u-editor.php u can configuration ,

        'core' => [
            'route' => [
                'middleware' => 'auth',
            ],
        ],
 the middleware is important!
 
 public/laravel-u-editor/ will  have  the  full UEditor assets.
 


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
    </script>


The detail useage Please see [http://ueditor.baidu.com](http://ueditor.baidu.com) 


## License

Laravel 5  UEditor is licensed under [The MIT License (MIT)](LICENSE).
