<?php
/**
 * Smtp发送
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\library\email;

use Yii;
use yii\helpers\ArrayHelper;
use yii\swiftmailer\Mailer;

class Smtp extends \yii\base\Object
{
    private $_error = '';
    private $_config = [];
    public $config = '';
    public $params = '';

    /**
     * 构造函数
     * @return
     */
    public function init()
    {
        $this->_config = ArrayHelper::merge($this->_config, Yii::$app->params['email.smtp']);
        if (is_array($this->config)) {
            $this->_config = ArrayHelper::merge($this->_config, $this->config);
        }
    }

    /**
     * 发送信息
     */
    public function send(array $vars)
    {
        $mailer = new Mailer(
            [
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => $this->_config['host'], //每种邮箱的host配置不一样
                    'username' => $this->_config['username'],
                    'password' => $this->_config['password'],
                    'port' => $this->_config['port'],
                    'encryption' => $this->_config['encryption'],
                ],
                'messageConfig' => [
                    'charset' => $this->_config['charset'],
                    'from' => isset($vars['from']) ? $vars['from'] : $this->_config['sender'],
                ],
            ]

        );
        $send = $mailer->compose();
        $send->setTo($vars['to']);
        $send->setSubject($vars['title']);
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $send->setHtmlBody($vars['content']); //发布可以带html标签的文本
        if ($send->send()) {
            return [
                'state' => 'success',
                'code' => 0,
                'message' => '发送成功',
                'params' => [],
            ];
        } else {
            $this->_error = '发送失败';
            return false;
        }
    }

    /**
     * 获取共享参数
     * @return [type] [description]
     */
    public function callback()
    {
        return [
            'username' => $this->_config['username'],
            'gateway' => $this->_config['gateway'],
        ];
    }

}
