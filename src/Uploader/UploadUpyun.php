<?php
namespace Stevenyangecho\UEditor\Uploader;
use Upyun\Upyun;
use Upyun\Config as UpyunConfig;


/**
 * trait UploadUpyun
 * 又拍云 上传 类
 * @package Stevenyangecho\UEditor\Uploader
 */
trait UploadUpyun{

    /**
     *  UEditorUpload.core.upyun 中的配置例子
     *  'upyun'=>[
     *      'bucket'    => 'your bucket',
     *      'operator'  => 'your operator',
     *      'password'  => 'your password',
     *      'domain'    => 'your domain',
     *      'protocol'  => 'http',
     *
     *      // bucket里面的相对路径
     *      'relativePath'  => '/project/ueditor/',
     *
     *      // 是否需要按照日期分开存放文件
     *      'isDateDir'  => true,
     *  ]
     */
    public function uploadUpyun($fullName){
        // 配置类对象
        $upConfig = config('UEditorUpload.core.upyun');
        $objConf = new UpyunConfig($upConfig['bucket'],$upConfig['operator'],$upConfig['password']);
        $Bucket = new Upyun($objConf);

        // 本地绝对路径
        $localFile = app()->publicPath().$fullName;
        // CDN文件名
        $remoteFileName = md5(microtime().rand(100000,999999)).".".@end(explode(".",$fullName));
        // CDN相对路径
        if($upConfig['isDateDir']){
            $remoteFilePath = $upConfig['relativePath'].date('Y_m_d').DIRECTORY_SEPARATOR.$remoteFileName;
        }else{
            $remoteFilePath = $upConfig['relativePath'].$remoteFileName;
        }
        // CDN绝对链接
        $remoteUrl = $upConfig['domain'].$remoteFilePath;
        // 上传到upyun bucket
        $result = $Bucket->write($remoteFilePath,fopen($localFile,'r'));
        // 删除本地文件
        unlink($localFile);
        // 判断状态是否成功
        if(isset($result['x-upyun-content-length']) && intval($result['x-upyun-content-length']) > 0){
            $this->fullName = $remoteUrl;
            $this->stateInfo = $this->stateMap[0];
        } else {
            $this->stateInfo= ' Upload Files To Remote CDN Server Failed On UpYunSdk Driver - 2048. ';
        }
        return true;
    }

}


