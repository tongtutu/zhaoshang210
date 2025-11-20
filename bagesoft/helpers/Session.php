<?php
/**
 * Session助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\helpers;

use Yii;

class Session
{

    /**
     * 设置session
     */
    public static function set($name, $value)
    {
        Yii::$app->session->set($name . '_' . Yii::$app->params['appid'], $value);
    }

    /**
     * 读取session
     */
    public static function get($name)
    {
        return Yii::$app->session->get($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 清除session
     */
    public static function remove($name)
    {
        Yii::$app->session->remove($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 清除所有session
     */
    public static function destroy()
    {
        Yii::$app->session->destroy();
    }

}
