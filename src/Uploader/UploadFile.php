<?php namespace Stevenyangecho\UEditor\Uploader;

use Stevenyangecho\UEditor\Uploader\Upload;

/**
 *
 *
 * Class UploadFile
 *
 * 文件/图像普通上传
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
class UploadFile  extends Upload{
    use UploadQiniu;
    use UploadAliOss;
    public function doUpload()
    {


        $file = $this->request->file($this->fileField);
        if (empty($file)) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return false;
        }
        if (!$file->isValid()) {
            $this->stateInfo = $this->getStateInfo($file->getError());
            return false;

        }

        $this->file = $file;

        $this->oriName = $this->file->getClientOriginalName();

        $this->fileSize = $this->file->getSize();
        $this->fileType = $this->getFileExt();

        $this->fullName = $this->getFullName();


        $this->filePath = $this->getFilePath();

        $this->fileName = basename($this->filePath);


        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return false;
        }
        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return false;
        }

        switch (config('UEditorUpload.core.mode')) {

            // 上传至本地
            case 'local' :

                try {
                    $this->file->move(dirname($this->filePath), $this->fileName);

                    $this->stateInfo = $this->stateMap[0];

                } catch (FileException $exception) {
                    $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
                    return false;
                }

                break;

            // 上传至七牛云
            case 'qiniu' :

                $content=file_get_contents($this->file->getPathname());
                return $this->uploadQiniu($this->filePath,$content);

                break;

            // 上传至阿里云
            case 'oss' :

                $content=file_get_contents($this->file->getPathname());
                return $this->uploadAliOss($this->filePath,$content);

                break;

            // 上传至 storage
            case 'storage' :

                $folder = config('UEditorUpload.core.storage.folder');
                if(config('UEditorUpload.core.storage.classifyByFileType')){
                    $folder .='/'. str_replace('.','',$this->fileType);
                }
                $path = \Storage::putFile($folder, $this->file);
                $this->fullName = \Storage::url($path);
                $this->stateInfo=$this->stateMap[0];

                break;

            // 上传至 ftp
            case 'ftp' :

                // 本地文件暂存目录   $this->getPathname()
                // 远程目录          $this->getFullName() UEditorUpload.php 配置中的 imagePathFormat
                return $this->uploadFtp($this->file->getPathname(), $this->getFullName());
                break;

            // 模式未定义 ERROR_UNKNOWN_MODE
            default :

                $this->stateInfo = $this->getStateInfo("ERROR_UNKNOWN_MODE");
                return false;
        }

        return true;

    }
}
