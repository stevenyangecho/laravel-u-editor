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

    use UploadQiniu,UploadUpyun;

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

        if(config('UEditorUpload.core.mode')=='local'){
            try {
                $this->file->move(dirname($this->filePath), $this->fileName);

                $this->stateInfo = $this->stateMap[0];

            } catch (FileException $exception) {
                $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
                return false;
            }

        }else if(config('UEditorUpload.core.mode')=='qiniu'){

            $content=file_get_contents($this->file->getPathname());
            return $this->uploadQiniu($this->filePath,$content);

        }else if(config('UEditorUpload.core.mode')=='upyun'){

            /**
             * 原理是将文件先存储到本地服务器，再调用接口转存到又拍云那边，然后把本地的文件删除
             */
            try {
                $this->file->move(dirname($this->filePath), $this->fileName);
                $this->stateInfo = $this->stateMap[0];
            } catch (FileException $exception) {
                $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
            }

            /**
             * 本地服务器上传失败或异常，直接报错
             */
            if($this->stateInfo == "SUCCESS") {
                return $this->uploadUpyun($this->fullName);
            }else{
                throw new \Exception(" Upload Files To Server Local Disk Failed On UpYunSdk Driver - 1024. ");
            }

        }else{
            $this->stateInfo = $this->getStateInfo("ERROR_UNKNOWN_MODE");
            return false;
        }

        return true;

    }
}
