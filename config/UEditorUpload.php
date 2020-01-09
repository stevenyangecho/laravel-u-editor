<?php

/* 前后端通信相关的配置,注释只允许使用多行方式 */
return [


    /*
    |--------------------------------------------------------------------------
    | 新增配置,route
    |--------------------------------------------------------------------------
    |
    |注意权限验证,请自行添加middleware
    |middleware 相当重要,请根据自己的项目设置,比如如果在后台使用,请设置为后台的auth middleware.
    |如果是单纯本机测试,请将
    |`// 'middleware' => 'auth',` 直接注释掉,如果留 `'middleware'=>''`空值,会产生bug,原因不详.
    |
    |
    */
    'core' => [
        'route' => [
            // 'middleware' => 'auth',
        ],

        /**
         * 上传方式说明
         *
         * local    本地
         * qiniu    七牛
         * oss      阿里云oss
         * storage  使用laravel的storage
         * ftp      上传至FTP服务器
         */
        'mode' => 'local',

        //七牛配置,若mode='qiniu',以下为必填.
        'qiniu' => [
            'accessKey' => '',
            'secretKey' => '',
            'bucket' => '',
            'url' => 'http://xxx.clouddn.com',//七牛分配的CDN域名,注意带上http://

        ],


        'oss' => [
            'driver' => 'oss',
            'access_id' => env('ALI_ACCESS_KEY_ID', ''),//'<Your Aliyun OSS AccessKeyId>',
            'access_key' => env('ALI_ACCESS_KEY_SECRET', ''),//'<Your Aliyun OSS AccessKeySecret>',
            'bucket' => env('ALI_OSS_BUCKET', ''),//'<OSS bucket name>',
            'endpoint' => env('ALI_OSS_ENDPOINT', 'oss-cn-hangzhou.aliyuncs.com'),//'<the endpoint of OSS, E.g: oss-cn-hangzhou.aliyuncs.com | custom domain, E.g:img.abc.com>', // OSS 外网节点或自定义外部域名
//                'endpoint_internal' => env('ALI_OSS_ENDPOINT_INTERNAL','oss-cn-hangzhou-internal.aliyuncs.com'),//'<internal endpoint [OSS内网节点] 如：oss-cn-shenzhen-internal.aliyuncs.com>', // v2.0.4 新增配置属性，如果为空，则默认使用 endpoint 配置(由于内网上传有点小问题未解决，请大家暂时不要使用内网节点上传，正在与阿里技术沟通中)
            'cdnDomain' => env('ALI_OSS_CDN_DOMAIN', ''),//'<CDN domain, cdn域名>', // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
            'ssl' => env('ALI_OSS_SSL', false),//<true|false> // true to use 'https://' and false to use 'http://'. default is false,
            'isCName' => env('ALI_OSS_IS_CNAME', true),//<true|false> // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名生成文件url， false: 则使用外部节点生成url
            'debug' => false//<true|false>
        ],
        'storage' => [
            'folder' => 'files',//注意不要加'/'
            'classifyByFileType' => false,//是否根据文件类型拆分到子文件夹

        ],

        // FTP 配置可以直接到 .env 文件中设置
        'ftp' => [
            // 域名 例：https://www.example.com/
            'domain' => env('U_EDITOR_FTP_DOMAIN', ''),

            // ftp地址 例：10.20.30.40
            'host' => env('U_EDITOR_FTP_HOST', ''),

            // 端口
            'port' => env('U_EDITOR_FTP_PORT', '21'),

            // 用户名密码
            'user' => env('U_EDITOR_FTP_USER', ''),
            'pass' => env('U_EDITOR_FTP_PASS', ''),

            // 多个host、多帐号密码用的分隔符，为了防止密码中如果有“,”会拆分错误
            'sep' => env('U_EDITOR_FTP_SEP', ','),

            // 文件夹和文件的权限
            'mode' => env('U_EDITOR_FTP_SEP', '0777'),

            // 是否开启日志输出
            'log' => env('U_EDITOR_FTP_LOG', 'true'),
        ],


    ],
    /**
     * 和原 UEditor /php/config.json 配置完全相同
     *
     */
    /* 上传图片配置项 */
    'upload' => [
        "imageActionName" => "uploadimage", /* 执行上传图片的action名称 */
        "imageFieldName" => "upfile", /* 提交的图片表单名称 */
        "imageMaxSize" => 2048000, /* 上传大小限制，单位B */
        "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
        "imageCompressEnable" => true, /* 是否压缩图片,默认是true */
        "imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
        "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
        "imageUrlPrefix" => "", /* 图片访问路径前缀 */
        "imagePathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
        /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
        /* {time} 会替换成时间戳 */
        /* {yyyy} 会替换成四位年份 */
        /* {yy} 会替换成两位年份 */
        /* {mm} 会替换成两位月份 */
        /* {dd} 会替换成两位日期 */
        /* {hh} 会替换成两位小时 */
        /* {ii} 会替换成两位分钟 */
        /* {ss} 会替换成两位秒 */
        /* 非法字符 \ : * ? " < > | */
        /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

        /* 涂鸦图片上传配置项 */
        "scrawlActionName" => "uploadscrawl", /* 执行上传涂鸦的action名称 */
        "scrawlFieldName" => "upfile", /* 提交的图片表单名称 */
        "scrawlPathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "scrawlMaxSize" => 2048000, /* 上传大小限制，单位B */
        "scrawlUrlPrefix" => "", /* 图片访问路径前缀 */
        "scrawlInsertAlign" => "none",

        /* 截图工具上传 */
        "snapscreenActionName" => "uploadimage", /* 执行上传截图的action名称 */
        "snapscreenPathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "snapscreenUrlPrefix" => "", /* 图片访问路径前缀 */
        "snapscreenInsertAlign" => "none", /* 插入的图片浮动方式 */

        /* 抓取远程图片配置 */
        "catcherLocalDomain" => ["127.0.0.1", "localhost", "img.baidu.com"],
        "catcherActionName" => "catchimage", /* 执行抓取远程图片的action名称 */
        "catcherFieldName" => "source", /* 提交的图片列表表单名称 */
        "catcherPathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "catcherUrlPrefix" => "", /* 图片访问路径前缀 */
        "catcherMaxSize" => 2048000, /* 上传大小限制，单位B */
        "catcherAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

        /* 上传视频配置 */
        "videoActionName" => "uploadvideo", /* 执行上传视频的action名称 */
        "videoFieldName" => "upfile", /* 提交的视频表单名称 */
        "videoPathFormat" => "/uploads/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "videoUrlPrefix" => "", /* 视频访问路径前缀 */
        "videoMaxSize" => 102400000, /* 上传大小限制，单位B，默认100MB */
        "videoAllowFiles" => [
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

        /* 上传文件配置 */
        "fileActionName" => "uploadfile", /* controller里,执行上传视频的action名称 */
        "fileFieldName" => "upfile", /* 提交的文件表单名称 */
        "filePathFormat" => "/uploads/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "fileUrlPrefix" => "", /* 文件访问路径前缀 */
        "fileMaxSize" => 51200000, /* 上传大小限制，单位B，默认50MB */
        "fileAllowFiles" => [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
        ], /* 上传文件格式显示 */

        /* 列出指定目录下的图片 */
        "imageManagerActionName" => "listimage", /* 执行图片管理的action名称 */
        "imageManagerListPath" => "/uploads/ueditor/php/upload/image/", /* 指定要列出图片的目录 */
        "imageManagerListSize" => 20, /* 每次列出文件数量 */
        "imageManagerUrlPrefix" => "", /* 图片访问路径前缀 */
        "imageManagerInsertAlign" => "none", /* 插入的图片浮动方式 */
        "imageManagerAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

        /* 列出指定目录下的文件 */
        "fileManagerActionName" => "listfile", /* 执行文件管理的action名称 */
        "fileManagerListPath" => "/uploads/ueditor/php/upload/file/", /* 指定要列出文件的目录 */
        "fileManagerUrlPrefix" => "", /* 文件访问路径前缀 */
        "fileManagerListSize" => 20, /* 每次列出文件数量 */
        "fileManagerAllowFiles" => [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
        ]] /* 列出的文件类型 */
];
