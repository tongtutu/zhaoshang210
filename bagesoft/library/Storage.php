<?php
/**
 * 存储
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 */

namespace bagesoft\library;

use bagesoft\helpers\Utils;
use bagesoft\models\Upload;
use Yii;

class Storage
{
    private static $_instance;
    private static $_driver = 'local';
    private static $_record = true;

    /**
     * 上传
     * @param  string $name 文件表单名
     * @param  array  $args 参数
     * @return array       上传结果
     */
    public static function upload($name, $args = [])
    {
        try {
            self::_driver($args);
            $result = self::$_instance->upload($name);
            if (is_array($result)) {
                self::_record($args);
            }
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 设置驱动
     * @param string $driver
     * @param array $params
     */
    private static function _driver($args = [])
    {
        if (! self::$_instance) {
            if ($args['_driver']) {
                $class = 'bagesoft\\library\\storage\\' . ucfirst(strtolower($args['_driver']));
            } elseif (self::$_driver) {
                $class = 'bagesoft\\library\\storage\\' . ucfirst(strtolower(self::$_driver));
            } else {
                $class = 'bagesoft\\library\\storage\\' . ucfirst(strtolower(Yii::$app->params['storage.driver']));
            }
            self::$_instance = new $class(['config' => $args]);
        }
    }

    /**
     * 上传记录入库
     * @return [type] [description]
     */
    private static function _record($args)
    {
        if (self::$_record == false) {
            return;
        }
        if (self::$_instance->uploadNum == 'one') {
            self::_todb(self::$_instance->upload, $args);
        } elseif (self::$_instance->uploadNum == 'multi') {
            foreach ((array) self::$_instance->upload as $key => $file) {
                self::_todb($file, $args);
            }
        }
    }

    /**
     * 入库
     * @param  array  $file [description]
     * @return [type]       [description]
     */
    private static function _todb(array $file, $args = [])
    {
        $storage             = new Upload();
        $storage->attributes = [
            'real_name'  => $file['name'],
            'file_name'  => $file['file'],
            'thumb_name' => $file['thumb'] ? $file['thumb'] : '',
            'save_path'  => $file['savepath'],
            'save_name'  => $file['savename'],
            'file_ext'   => $file['ext'],
            'file_mime'  => $file['type'],
            'file_size'  => $file['size'],
        ];

        if (! $storage->save()) {
            throw new \Exception('数据入库失败');
        }
    }
}
