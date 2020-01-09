<?php

namespace Stevenyangecho\UEditor\Uploader;

use Illuminate\Log\Writer;
use Monolog\Logger;

/**
 *
 * trait UploadFtp
 * FTP 上传 类
 *
 * 提交者：imba97
 *
 * 参考教程：https://www.cnblogs.com/phproom/p/9683612.html
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
trait UploadFtp
{

    public function uploadFtp($localFile, $remoteFile)
    {
        $ftp_domain = config('UEditorUpload.core.ftp.domain');

        $ftp_host = config('UEditorUpload.core.ftp.host');
        $ftp_port = config('UEditorUpload.core.ftp.port');
        $ftp_user = config('UEditorUpload.core.ftp.user');
        $ftp_pass = config('UEditorUpload.core.ftp.pass');

        $ftp_sep = config('UEditorUpload.core.ftp.sep');
        $ftp_log = config('UEditorUpload.core.ftp.log');

        if(!empty($ftp_host)) {
            $ftp_host_list = explode($ftp_sep, $ftp_host);
        }

        if(!isset($ftp_host_list)) return false;

        // 端口
        $ftp_port_list = explode($ftp_sep, $ftp_port);

        // 如果是多个 ftp 地址
        if(count($ftp_host_list) > 1) {
            $ftp_conn = array();

            // 循环连接 FTP
            foreach($ftp_host_list as $index => $host) {

                // 如果只有一个 port 就默认等于第一个
                $port = $ftp_port_list[0];
                // 如果多个则对应 host 设置
                if(count($ftp_port_list) > 1) {
                    $port = $ftp_port_list[$index];
                }

                if(($conn_tmp = @ftp_connect($host, $port)) == false) {
                    if($ftp_log == 'true')
                        $this->ftp_create_log('FTP Connect Error - HOST:' . $host);
                    return false;
                }

                $ftp_conn[] = $conn_tmp;
            }
        } else {
            if(($ftp_conn = @ftp_connect($ftp_host_list[0], $ftp_port_list[0])) == false) {
                if($ftp_log == 'true')
                    $this->ftp_create_log('FTP Connect Error - HOST:' . $ftp_host_list[0]);
                return false;
            }
        }

        // 如果有多个用户名密码 则分开与之对应的 host 登录
        $ftp_user_list = explode($ftp_sep, $ftp_user);
        $ftp_pass_list = explode($ftp_sep, $ftp_pass);

        // 单 host 返回结果
        $result = true;
        // 多 host 返回结果
        $result_list = array();

        // 登录FTP
        if(is_array($ftp_conn)) {

            // 数组则循环登录
            foreach($ftp_conn as $index => $conn) {

                // 相同用户名密码
                $user = $ftp_user;
                $pass = $ftp_pass;

                // 多用户名密码
                if(count($ftp_user_list) > 1 && count($ftp_user_list) > 1) {
                    $user = $ftp_user_list[$index];
                    $pass = $ftp_pass_list[$index];
                }

                // 执行登录，失败记录日志
                if(!@ftp_login($conn,$user,$pass)) {
                    if($ftp_log == 'true')
                        $this->ftp_create_log('Login Error - HOST:' . $ftp_host_list[$index] . ';USER:' . $user . ';PASS:' . $pass);
                    return false;
                }

                $result_list[] = $this->doUploadFileFtp($conn, $localFile, $remoteFile, true);
            }
        } else {
            if(!@ftp_login($ftp_conn,$ftp_user,$ftp_pass)) {
                if($ftp_log == 'true')
                    $this->ftp_create_log('Login Error - HOST:' . $ftp_host_list[$index] . ';USER:' . $user . ';PASS:' . $pass);
                return false;
            }

            $result = $this->doUploadFileFtp($ftp_conn, $localFile, $remoteFile, true);
        }

        if(count($result_list) > 0) {
            foreach ($result_list as $res) {
                // 任意一个没成功会都会返回错误
                if(!$res) {
                    $result = false;
                    break;
                }
            }
        }

        if(!$result) {
            // upload_error
            $this->stateInfo = $this->stateMap[2];
            return false;
        }

        $this->fullName = $ftp_domain . $remoteFile;

        $this->stateInfo = $this->stateMap[0];

        return true;
    }

    private function doUploadFileFtp($conn, $localFile, $remoteFile, $permissions = null) {

        $ftp_log = config('UEditorUpload.core.ftp.log');

        // 创建文件夹
        $remote_dir = dirname($remoteFile);
        $path_arr = explode('/', $remote_dir);

        $path_div = count($path_arr);

        foreach($path_arr as $dirName) {

            // 切换文件夹成功则执行切换下一级文件夹
            if(@ftp_chdir($conn, $dirName) == true) continue;

            // 切换文件夹失败则创建文件夹
            $tmp = @ftp_mkdir($conn, $dirName);

            if($tmp == false) {
                if($ftp_log == 'true')
                    $this->ftp_create_log('Create Dir Error - DirName:' . $dirName);
                return false;
            }

            @ftp_chdir($conn, $dirName);

            if ($permissions !== null) {
                // 修改目录权限
                @ftp_chmod($conn,$dirName,$permissions);
            }

        }

        // 回退到根,因为上面的目录切换导致当前目录不在根目录
        for($i=0;$i<$path_div;$i++)
        {
            @ftp_cdup($conn);
        }

        if(@ftp_put($conn, $remoteFile, $localFile,FTP_BINARY)) {
            return true;
        }

        return false;

    }

    private function ftp_create_log($log_info) {
        $log = new Writer(new Logger('signin'));
        $log->useDailyFiles(storage_path().'/logs/ftp.log',30);
        $log->write('info', $log_info);
    }
}
