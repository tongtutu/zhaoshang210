<?php
/**
 * 七牛上传
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 */
namespace bagesoft\library\storage;

use bagesoft\models\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Yii;
use yii\helpers\ArrayHelper;

class Qiniu extends \bagesoft\library\storage\Base
{
    //上传完成文件
    public $upload = null;
    //上传数量 one/multi
    public $uploadNum = 0;
    //七牛token
    private $_token;
    //七牛上传对象
    private $_uploadMgr;

    /**
     * 构造函数
     * @return
     */
    public function init()
    {
        parent::init();
        $this->_config = ArrayHelper::merge($this->_config, Yii::$app->params['storage.qiniu']);
        $this->_token = (new Auth($this->_config['accessKey'], $this->_config['secretKey']))->uploadToken($this->_config['bucket']);
        $this->_uploadMgr = new UploadManager();
    }

    /**
     * 上传
     * @param  string $name     文件表单名称
     * @return
     */
    public function upload($name = '')
    {
        $files = parent::fileParse($name);
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                $fileinfo = parent::config($file)->thirdCheck($this->_config['root']);
                if ($fileinfo) {
                    $this->upload[] = $this->_qiniu($fileinfo);
                }
            }
            $this->uploadNum = 'multi';
        } elseif (is_object($files)) {
            $fileinfo = parent::config($files)->thirdCheck($this->_config['root']);
            if (false == $fileinfo) {
                return $files->getError();
            } else {
                $this->upload = $this->_qiniu($fileinfo);
            }

            $this->uploadNum = 'one';
        }
        return $this->upload;
    }

    /**
     * 七牛上传
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    private function _qiniu($file)
    {
        $ltrim = './';
        $fileFmt = str_replace('\\', '/', ltrim($file->getSaveName(), $ltrim));
        list($ret, $err) = $this->_uploadMgr->putFile($this->_token, str_replace('./', '', $file->getSaveName()), $file->getRealPath());
        if ($err) {
            throw new \Exception('上传失败.qiniu');
        } else {
            return [
                'name' => $file->getInfo('name'),
                'type' => $file->getMime(),
                'size' => $file->getSize(),
                'ext' => $file->getExt(),
                'savename' => str_replace(dirname($fileFmt) . '/', '', $fileFmt),
                'savepath' => dirname($fileFmt),
                'file' => $fileFmt,
                'fileurl' => Yii::$app->request->hostInfo . Yii::getAlias('@web') . '/' . $fileFmt,
            ];
        }
    }

}
