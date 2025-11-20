<?php
/**
 * 缓存助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\helpers;

use Yii;
use yii\helpers\Html;

class Cache
{
    /**
     * 设置cache
     * @param array $arrs cache内容
     */
    public static function set($arrs)
    {
        Yii::$app->cache->set($arrs['name'] . '_' . Yii::$app->params['appid'], $arrs['value'], $arrs['duration']);
    }

    /**
     * 读取cache
     * @param  string $name cache名称
     * @return
     */
    public static function get($name)
    {
        return $cache = Yii::$app->cache->get($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 删除cache
     * @param  string $name cache名称
     * @return
     */
    public static function delete($name)
    {
        Yii::$app->cache->delete($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 检测cache
     * @param  string $name cache名称
     * @return
     */
    public static function exists($name)
    {
        return Yii::$app->cache->exists($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 核心缓存
     * @param  string $name 缓存名称
     * @return array
     */
    public static function core($name)
    {
        try {
            switch ($name) {
                case '_wordban':
                    return \bagesoft\models\Wordban::_cache();
                    break;
                default:
                    throw new \Exception(Html::encode($name) . '.cached not exists');
                    break;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
