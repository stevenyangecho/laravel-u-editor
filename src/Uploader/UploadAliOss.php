<?php namespace Stevenyangecho\UEditor\Uploader;
use OSS\OssClient;

/**
 *
 *
 * trait UploadAliOss
 *
 * 阿里 上传 类
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
trait UploadAliOss
{
    public function uploadAliOss($key, $content)
    {
        $accessId  = config('UEditorUpload.core.oss.access_id');
        $accessKey = config('UEditorUpload.core.oss.access_key');
        $endPoint  = config('UEditorUpload.core.oss.endpoint'); // 默认作为外部节点
        $epInternal= empty(config('UEditorUpload.core.oss.endpoint_internal')) ? $endPoint : config('UEditorUpload.core.oss.endpoint_internal'); // 内部节点
        $cdnDomain = empty(config('UEditorUpload.core.oss.cdnDomain')) ? '' : config('UEditorUpload.core.oss.cdnDomain');
        $bucket    = config('UEditorUpload.core.oss.bucket');
        $ssl       = empty(config('UEditorUpload.core.oss.ssl')) ? false : config('UEditorUpload.core.oss.ssl');
        $isCname   = empty(config('UEditorUpload.core.oss.isCName')) ? false : config('UEditorUpload.core.oss.isCName');

        $client  = new OssClient($accessId, $accessKey, $epInternal, $isCname);

        $client->putObject($bucket,$key,$content);
        $client->putObjectAcl($bucket,$key,'public-read');

        $this->fullName = ( $ssl ? 'https://' : 'http://' ) . ( $isCname ? ( $cdnDomain == '' ? $endPoint : $cdnDomain ) : $bucket . '.' . $endPoint ) . '/' . ltrim($key, '/');

        $this->stateInfo = $this->stateMap[0];

        return true;
    }
}
