<?php
/**
 * 本地上传类
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\library\storage;

use bagesoft\library\storage\File;
use bagesoft\models\Config;
use yii\helpers\ArrayHelper;

class Base extends \yii\base\BaseObject
{
    // 用户输入参数
    public $config;
    private $file = [];

    // 默认配置
    protected $_config = [
        'mimes' => '',  // 允许上传的文件MiMe类型
        'size' => 0,  // 上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png',  // 允许上传的文件后缀
        'root' => './uploads',  // 保存根路径
        'replace' => true,  // 存在同名是否覆盖
        'quality' => 100,  // 存在同名是否覆盖
        'rule' => 'Ym',  // 目录及命名规则
        'thumb' => false,  // 是否保存缩略图
        'thumbSize' => '200x300',  // 缩略图尺寸
        'water' => false,  // 是否打水印
        'waterFile' => './static/watermark.png',  // 水印文件,
        'waterAlpha' => 100,  // 水印透明度
        'waterPos' => 9,  // 水印位置
    ];

    /**
     * 构造函数
     * @return
     */
    public function init()
    {
        $sysconf = Config::_loadAll(['module' => 'upload']);

        $this->_config['size'] = $sysconf['upload_max_size'];
        $this->_config['exts'] = $sysconf['upload_allow_ext'];
        $this->_config['root'] = $sysconf['upload_root'];
        $this->_config['water'] = $sysconf['upload_water'] == 'open' ? true : false;
        $this->_config['waterFile'] = $sysconf['upload_water_file'];
        $this->_config['waterAlpha'] = $sysconf['upload_water_alpha'];
        $this->_config['waterPos'] = $sysconf['upload_water_pos'];
        $this->_config['thumb'] = $sysconf['upload_thumb'] == 'open' ? true : false;
        $this->_config['thumbSize'] = $sysconf['upload_thumb_size'];
        $this->_config['quality'] = $sysconf['upload_quality'];
        $this->_config['rule'] = $sysconf['upload_rule'] ? $sysconf['upload_rule'] : 'Ym';
        if (is_array($this->config)) {
            $this->_config = ArrayHelper::merge($this->_config, $this->config);
        }
        if (substr($this->_config['waterFile'], 0, 1) != '.') {
            $this->_config['waterFile'] = './' . $this->_config['waterFile'];
        }
    }

    /**
     * 应用配置参数
     * @param [type] $fileinfo [description]
     */
    public function config($fileinfo)
    {
        $validate = false;
        if ($this->_config['size'] > 0) {
            $validate['size'] = $this->_config['size'] * 1024;
        }
        if ($this->_config['exts']) {
            $validate['ext'] = $this->_config['exts'];
        }
        if ($this->_config['mimes']) {
            $validate['type'] = $this->_config['mimes'];
        }
        if ($validate) {
            $fileinfo->validate($validate);
        }
        $fileinfo->rule($this->_config['rule']);
        return $fileinfo;
    }

    /**
     * 解析上传文件
     * @access public
     * @param string|array $name 名称
     * @return null|array|\think\File
     */
    public function fileParse($name = '')
    {
        if (empty($this->file)) {
            $this->file = isset($_FILES) ? $_FILES : [];
        }
        if (is_array($name)) {
            return $this->file = array_merge($this->file, $name);
        }
        $files = $this->file;
        if (!empty($files)) {
            // 处理上传文件
            $array = [];
            foreach ($files as $key => $file) {
                if (is_array($file['name'])) {
                    $item = [];
                    $keys = array_keys($file);
                    $count = count($file['name']);
                    for ($i = 0; $i < $count; $i++) {
                        if (empty($file['tmp_name'][$i])) {
                            continue;
                        }
                        $temp['key'] = $key;
                        foreach ($keys as $_key) {
                            $temp[$_key] = $file[$_key][$i];
                        }
                        $item[] = (new File($temp['tmp_name']))->setUploadInfo($temp);
                    }
                    $array[$key] = $item;
                } else {
                    if ($file instanceof File) {
                        $array[$key] = $file;
                    } else {
                        if (empty($file['tmp_name'])) {
                            continue;
                        }
                        $array[$key] = (new File($file['tmp_name']))->setUploadInfo($file);
                    }
                }
            }
            if (strpos($name, '.')) {
                list($name, $sub) = explode('.', $name);
            }
            if ('' === $name) {
                // 获取全部文件
                return $array;
            } elseif (isset($sub) && isset($array[$name][$sub])) {
                return $array[$name][$sub];
            } elseif (isset($array[$name])) {
                return $array[$name];
            }
        }
        return null;
    }
}
