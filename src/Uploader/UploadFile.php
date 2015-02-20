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
    public function doUpload()
    {
        $file = $this->request->file($this->fileField);
        if (!$file) {
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
        try {
            $this->file->move(dirname($this->filePath), $this->fileName);

            $this->stateInfo = $this->stateMap[0];

        } catch (FileException $exception) {
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
            return false;
        }


        return true;

    }
}