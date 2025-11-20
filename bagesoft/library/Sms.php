<?php
/**
 * 短信发送
 * @author        shuguang <5565907@qq.com>
 */

namespace bagesoft\library;

use bagesoft\constant\SendConst;
use bagesoft\functions\SendTplFunc;
use bagesoft\helpers\Utils;
use bagesoft\models\SendLog;
use Yii;

class Sms
{
    private static $_instance;
    private static $_driver = '';

    /**
     * 发送
     * @param  array $vars 发送参数
     * @param  array  $args 追加参数
     * @return object
     */
    public static function send($vars, $args = [])
    {
        try {
            self::_driver($args);
            if (isset($vars['_tpl']) && !isset($vars['content'])) {
                $replace = SendTplFunc::paramsReplace($vars);
                if (false == $replace) {
                    throw new \Exception('不存在的消息模板');
                }
                $vars['content'] = $replace['content'];
                $vars['extend'] = $replace['extend'];
            }
            $result = self::$_instance->send($vars);
            if (is_array($result)) {
                self::_logWrite($vars, $result);
                return $result;
            } else {
                throw new \Exception($result);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 日志记录
     * @param  array $vars     参数
     * @param  array $callback 回调参数
     * @return object
     */
    private static function _logWrite($vars, $callback = null)
    {
        $getParams = self::$_instance->callback();
        $sendLog = new SendLog();
        foreach ((array) $vars as $key => $row) {
            if (in_array($key, $sendLog->attributes())) {
                $sendLog->$key = $row;
            }
        }
        $sendLog->channel = SendConst::CHANNEL_SMS;
        $sendLog->act = isset($vars['_tpl']) ? $vars['_tpl'] : 'none';
        $sendLog->acc = $getParams['username'] ? (string) $getParams['username'] : '';
        $sendLog->target = $vars['target'] ? (string) $vars['target'] : '';
        $sendLog->gateway = $getParams['gateway'] ? (string) $getParams['gateway'] : '';
        $sendLog->date_at = date('Ymd');
        $sendLog->callback = Utils::jsonStr($callback);
        if (!$sendLog->save()) {
            throw new \Exception('日志保存失败');
        }
    }

    /**
     * 设置驱动
     * @param array $args   参数
     */
    private static function _driver($args = [])
    {
        if (!self::$_instance) {
            if (isset($args['_driver'])&& $args['_driver']) {
                $class = 'bagesoft\\library\\sms\\' . ucfirst(strtolower($args['_driver']));
            } elseif (self::$_driver) {
                $class = 'bagesoft\\library\\sms\\' . ucfirst(strtolower(self::$_driver));
            } else {
                $class = 'bagesoft\\library\\sms\\' . ucfirst(strtolower(Yii::$app->params['sms.driver']));
            }
            self::$_instance = new $class(['config' => $args]);
        }
    }
}
