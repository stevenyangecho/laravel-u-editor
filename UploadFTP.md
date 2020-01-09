## UploadFTP

使用FTP协议上传图片等

## 教程

主要就是修改配置

> ### .env

```dotenv
# FTP 域名
U_EDITOR_FTP_DOMAIN=https://www.example.com/

# FTP 地址
U_EDITOR_FTP_HOST=10.20.30.40

# FTP 端口
U_EDITOR_FTP_PORT=21

# FTP 用户
U_EDITOR_FTP_USER=imba97

# FTP 密码
U_EDITOR_FTP_PASS=233

# 分隔符
U_EDITOR_FTP_SEP=,

# 创建文件夹的权限
U_EDITOR_FTP_MODE=0777

# 是否记录日志
U_EDITOR_FTP_LOG=true
```

支持多域名，例如：

```dotenv
U_EDITOR_FTP_HOST=10.20.30.40,11.21.31.41
U_EDITOR_FTP_PORT=21,6000
U_EDITOR_FTP_USER=host1User,host2User
U_EDITOR_FTP_PASS=host1Pass,host2Pass
```


如果两个服务器的端口、账号名、密码相同，你可以只配置一次，例如：

```dotenv
U_EDITOR_FTP_HOST=10.20.30.40,11.21.31.41
U_EDITOR_FTP_PORT=21
U_EDITOR_FTP_USER=imba97
U_EDITOR_FTP_PASS=233
```

`U_EDITOR_FTP_SEP` 的作用是，如果你密码中有“,”，你可以换一个符号作为分隔符，例如：
```dotenv
U_EDITOR_FTP_HOST=10.20.30.40|11.21.31.41
U_EDITOR_FTP_PORT=21|6000
U_EDITOR_FTP_USER=host1User|host2User
U_EDITOR_FTP_PASS=host1Pass|host2Pass
U_EDITOR_FTP_SEP=|
```

---

> ### UEditorUpload.php

```php

'core' => [
   'mode' => 'ftp'
]

// ...

'upload' => [
   "imagePathFormat" => "uploads/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
]
```
mode 改成 ftp

upload 的 imagePathFormat 改成你要上传到的 FTP 服务器中的哪个位置，文件夹不存在会自动创建，最后返回的图片地址会与域名拼接，变成：
`https://www.example.com/uploads/image/20200109/xxxxxxx.png`

## 吐槽

我太难了，为啥没有 FTP，写到加班 GG

传其他文件可能有BUG，我只用到本地上传图片，单图和多图都没问题，就这样，我不管我要下班
