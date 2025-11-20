<?php
/**
 * IP归属地查询
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 */

namespace bagesoft\library;

class IpRegion
{
    private static $_instance;
    private static $_driver = 'ipip';

    /**
     * 搜索
     * @param  string $ip ip地址
     * @return array
     */
    public static function find($ip, $args = [])
    {
        try {
            self::_driver($args);
            return self::$_instance->find($ip);
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
                $class = 'bagesoft\\library\\ipRegion\\' . ucfirst(strtolower($args['_driver']));
            } elseif (self::$_driver) {
                $class = 'bagesoft\\library\\ipRegion\\' . ucfirst(strtolower(self::$_driver));
            } else {
                $class = 'bagesoft\\library\\ipRegion\\' . ucfirst(strtolower(Yii::$app->params['ipRegion.driver']));
            }
            self::$_instance = new $class(['config' => $args]);
        }
    }
}
