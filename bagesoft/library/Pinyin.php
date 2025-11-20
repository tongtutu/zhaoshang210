<?php
/**
 * 汉语化拼音
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 */

namespace bagesoft\library;

class Pinyin
{
    private static $_instance;
    private static $_driver = 'basic';

    /**
     * 搜索
     * @param  string $string 文字
     * @return array
     */
    public static function trans($string, $args = [])
    {
        try {
            self::_driver($args);
            return self::$_instance->trans($string);
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
        if (!self::$_instance) {
            if ($args['_driver']) {
                $class = 'bagesoft\\library\\pinyin\\' . ucfirst(strtolower($args['_driver']));
            } elseif (self::$_driver) {
                $class = 'bagesoft\\library\\pinyin\\' . ucfirst(strtolower(self::$_driver));
            } else {
                $class = 'bagesoft\\library\\pinyin\\' . ucfirst(strtolower(Yii::$app->params['pinyin.driver']));
            }
            self::$_instance = new $class(['config' => $args]);
        }
    }
}
