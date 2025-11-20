<?php
/**
 * 工具助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\helpers;

use bagesoft\helpers\File;
use Yii;

class Tools
{
    /**
     * 调试信息
     * @return [type] [description]
     * system : 系统
     * wechat.pay:微信支付
     * wechat.hello:微信接入
     */
    public static function debug($message, $file = 'system')
    {
        $time = date('Y-m-d H:i:s');
        $logdir = Yii::getAlias("@runtime") . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        $logfile = $logdir . 'bage.' . $file . '.log';
        if (!is_dir($logdir)) {
            File::createDir($logdir);
        }
        if (!is_file($logfile)) {
            touch($logfile);
        }

        if (is_array($message)) {
            $content = var_export($message, true);
        } else {
            $content = $message;
        }

        File::writeFile($logfile, "\r\n" . $time . "\r\n" . $content . "\r\n------------------", 2);
    }
}
