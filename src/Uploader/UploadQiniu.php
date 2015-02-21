<?php namespace Stevenyangecho\UEditor\Uploader;

use \Qiniu\Storage\UploadManager;
use \Qiniu\Auth;

/**
 *
 *
 * trait UploadQiniu
 *
 * 七牛 上传 类
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
trait UploadQiniu
{
    /**
     * 获取文件路径
     * @return string
     */
    protected function getFilePath()
    {
        $fullName = $this->fullName;


        $fullName = ltrim($fullName, '/');


        return $fullName;
    }

    public function uploadQiniu($key, $content)
    {
        $upManager = new UploadManager();
        $auth = new Auth(config('UEditorUpload.core.qiniu.accessKey'), config('UEditorUpload.core.qiniu.secretKey'));
        $token = $auth->uploadToken(config('UEditorUpload.core.qiniu.bucket'));

        list($ret, $error) = $upManager->put($token, $key, $content);
        if ($error) {
            $this->stateInfo= $error->message();
        } else {
            //change $this->fullName ,return the url
            $url=rtrim(strtolower(config('UEditorUpload.core.qiniu.url')),'/');
            $fullName = ltrim($this->fullName, '/');
            $this->fullName=$url.'/'.$fullName;
            $this->stateInfo = $this->stateMap[0];
        }
        return true;
    }
}