<?php
/**
 * 邮件发送
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 */

namespace bagesoft\library;

use bagesoft\models\SendLog;
use bagesoft\models\SendTpl;

class Email
{
    private static $_instance;
    private static $_driver = 'sendcloud';

    /**
     * 搜索
     * @param  string $vars 变量数组
     * @return array
     */
    public static function send($vars, $args = [])
    {
        try {
            self::_driver($args);

            if (isset($vars['_tpl']) && !isset($vars['content'])) {
                $replace = SendTpl::paramsReplace($vars);
                if (false == $replace) {
                    return false;
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
     * @return [type] [description]
     */
    private static function _logWrite($vars, $callback = null)
    {
        $getParams = self::$_instance->callback();
        $log = new SendLog();
        foreach ($vars as $key => $row) {
            if (in_array($key, $log->attributes())) {
                $log->$key = $row;
            }
        }
        $log->module = 'email';
        $log->action = isset($vars['_tpl']) ? $vars['_tpl'] : 'none';
        $log->account = $getParams['username'] ? $getParams['username'] : '';
        $log->target = $vars['target'] ? $vars['target'] : '';
        $log->source = $getParams['source'] ? $getParams['source'] : $getParams['username'];
        $log->gateway = $getParams['gateway'] ? $getParams['gateway'] : '';
        $log->date_at = date('Ymd');
        $log->callback = $callback ? serialize($callback) : '';
        if (!$log->save()) {
            throw new \Exception('日志保存失败');
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
            $class = isset($args['config']['_driver']) ? 'bagesoft\\library\\email\\' . ucfirst(strtolower($args['config']['_driver'])) : 'bagesoft\\library\\email\\' . ucfirst(strtolower(self::$_driver));
            self::$_instance = new $class($args);
        }
    }
}
