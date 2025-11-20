<?php
/**
 * Cookie助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\helpers;

use Yii;

class Cookies
{

    /**
     * 设置cookies
     */
    public static function set($arrs)
    {
        $arrs['name'] = $arrs['name'] . '_' . Yii::$app->params['appid'];
        Yii::$app->response->cookies->add(new \yii\web\Cookie($arrs));
    }

    /**
     * 读取cookies
     */
    public static function get($name)
    {
        return Yii::$app->request->cookies->getValue($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 清除cookies
     */
    public static function remove($name)
    {
        Yii::$app->response->cookies->remove($name . '_' . Yii::$app->params['appid']);
    }

}
