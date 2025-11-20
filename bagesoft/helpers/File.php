<?php
/**
 * 文件助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\helpers;

use yii\helpers\ArrayHelper;

class File
{
    /**
     * 创建空文件
     *
     * @param string $filename  需要创建的文件
     *
     * @return mixed
     */
    public static function createFile($filename)
    {
        if (is_file($filename)) {
            return false;
        }

        self::createDir(dirname($filename)); //创建目录
        return file_put_contents($filename, '');
    }

    /**
     * 写文件
     *
     * @param  string $filename  文件名称
     * @param  string $content   写入文件的内容
     * @param  string $type   类型，1=清空文件内容，写入新内容，2=再内容后街上新内容
     *
     * @return bool
     */
    public static function writeFile($filename, $content, $type = 1)
    {
        if ($type == 1) {
            is_file($filename) && self::delFile($filename); //删除文件
            self::createFile($filename);
            self::writeFile($filename, $content, 2);
            return true;
        } else {
            if (!is_writable($filename)) {
                return false;
            }

            $handle = fopen($filename, 'a');
            if (!$handle) {
                return false;
            }

            $result = fwrite($handle, $content);
            if (!$result) {
                return false;
            }

            fclose($handle);
            return true;
        }
    }

    /**
     * 拷贝一个新文件
     *
     * @param  string $filename    文件名称
     * @param  string $newfilename 新文件名称
     *
     * @return bool
     */
    public static function copyFile($filename, $newfilename)
    {
        if (!is_file($filename) || !is_writable($filename)) {
            return false;
        }

        self::createDir(dirname($newfilename)); //创建目录
        return copy($filename, $newfilename);
    }

    /**
     * 移动文件
     *
     * @param  string $filename 文件名称
     * @param  string $newfilename 新文件名称
     *
     * @return bool
     */
    public static function moveFile($filename, $newfilename)
    {
        if (!is_file($filename) || !is_writable($filename)) {
            return false;
        }

        self::createDir(dirname($newfilename)); //创建目录
        return rename($filename, $newfilename);
    }

    /**
     * 删除文件
     *
     * @param string $filename  文件名称
     *
     * @return bool
     */
    public static function delFile($filename)
    {
        if (!is_file($filename) || !is_writable($filename)) {
            return true;
        }

        return unlink($filename);
    }

    /**
     * 获取文件信息
     *
     * @param string $filename  文件名称
     *
     * @return array('上次访问时间','inode 修改时间','取得文件修改时间','大小'，'类型')
     */
    public static function getFileInfo($filename)
    {
        if (!is_file($filename)) {
            return false;
        }

        return array(
            'atime' => date("Y-m-d H:i:s", fileatime($filename)),
            'ctime' => date("Y-m-d H:i:s", filectime($filename)),
            'mtime' => date("Y-m-d H:i:s", filemtime($filename)),
            'size' => filesize($filename),
            'type' => filetype($filename),
        );
    }

    /**
     * 创建目录
     *
     * @param string  $path   目录
     *
     * @return bool
     */
    public static function createDir($path)
    {
        if (is_dir($path)) {
            return true;
        }

        self::createDir(dirname($path));
        mkdir($path);
        chmod($path, 0777);
        return true;
    }

    /**
     * 删除目录
     *
     * @param string $path 目录
     *
     * @return bool
     */
    public static function delDir($path)
    {
        $succeed = true;
        if (is_dir($path)) {
            $objDir = opendir($path);
            while (false !== ($fileName = readdir($objDir))) {
                if (($fileName != '.') && ($fileName != '..')) {
                    chmod("$path/$fileName", 0777);
                    if (!is_dir("$path/$fileName")) {
                        if (!unlink("$path/$fileName")) {
                            $succeed = false;
                            break;
                        }
                    } else {
                        self::delDir("$path/$fileName");
                    }
                }
            }
            if (!readdir($objDir)) {
                closedir($objDir);
                if (!rmdir($path)) {
                    $succeed = false;
                }
            }
        }
        return $succeed;
    }

    /**
     * 列出文件夹列表
     *
     * @param $dirname
     * @return array
     */
    public static function listDir($dirname)
    {
        $files = array();
        if (is_dir($dirname)) {
            $fileHander = opendir($dirname);
            while (($file = readdir($fileHander)) !== false) {
                $filepath = $dirname . '/' . $file;
                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0 || is_file($filepath)) {
                    continue;
                }
                $files[] = \bagesoft\helpers\Utils::autoCharset($file, 'GBK', 'UTF8');
            }
            closedir($fileHander);
        } else {
            $files = false;
        }
        return $files;
    }

    /**
     * 列出文件列表
     *
     * @param $dirname
     * @return array
     */
    public static function listFile($dirname)
    {
        $files = array();
        if (is_dir($dirname)) {
            $fileHander = opendir($dirname);
            while (($file = readdir($fileHander)) !== false) {
                $filepath = $dirname . '/' . $file;

                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0 || is_dir($filepath)) {
                    continue;
                }
                $files[] = \bagesoft\helpers\Utils::autoCharset($file, 'GBK', 'UTF8');
            }
            closedir($fileHander);
        } else {
            $files = false;
        }
        return $files;
    }

    /**
     * 新建目录
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function makeFilePath($params)
    {
        //默认规则
        $default = ['path' => 1, 'nameRule' => 1];
        $params = ArrayHelper::merge($default, $params);
        //根目录
        if (isset($params['root'])) {
            $root = $params['root'];
        } else {
            $root = 'uploads/';
        }

        switch ($params['path']) {
            case '1':$path = date('Ymd');
                break;
            case '2':$path = date('Ym');
                break;
            case '3':$path = date('Y');
                break;
            default:$path = $params['path'];
                break;
        }

        switch ($params['subpath']) {
            case '1':$path = date('Ymd');
                break;
            case '2':$path = date('Ym');
                break;
            case '3':$path = date('Y');
                break;
            default:$path = $params['path'];
                break;
        }
    }

    /**
     * 获取文件扩展名
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public static function ext($file)
    {
        if (strstr($file, '.') && $file) {
            return substr(strrchr($file, '.'), 1);
        }
    }

}
