<?php
/**
 * sendcloud 邮件发送接口
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 * 接口地址
 * http://sendcloud.sohu.com/doc/
 *
 */

namespace bagesoft\library\email;

use Yii;
use yii\helpers\ArrayHelper;

class Sendcloud extends \yii\base\Object
{
    private $_error = '';
    private $_config = [];
    public $config = '';
    public $params = '';
    private $_url = 'http://api.sendcloud.net/apiv2/mail/send';

    /**
     * 构造函数
     * @return
     */
    public function init()
    {
        $this->_config = ArrayHelper::merge($this->_config, Yii::$app->params['email.sendcloud']);
        if (is_array($this->config)) {
            $this->_config = ArrayHelper::merge($this->_config, $this->config);
        }
    }

    /**
     * 发送信息
     */
    public function send(array $vars)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_URL, $this->_url);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                array(
                    'apiUser' => $this->_config['apiUser'], # 使用api_user和api_key进行验证
                    'apiKey' => $this->_config['apiKey'],
                    'from' => $this->_config['sender'], # 发信人，用正确邮件地址替代
                    'fromName' => $this->_config['from'],
                    'to' => $vars['target'], # 收件人地址，用正确邮件地址替代，多个地址用';'分隔
                    'subject' => $vars['title'],
                    'html' => $vars['content'],
                )
            );
            $exec = curl_exec($ch);
            $result = \yii\helpers\Json::decode($exec);
            if ($result['result'] == false) {
                throw new \Exception(curl_error($ch));
            } else {
                return [
                    'state' => 'success',
                    'code' => 0,
                    'message' => '发送成功',
                    'params' => $result,
                ];
            }
            curl_close($ch);

        } catch (\Exception $e) {
            return $result['message'];
        }

    }

    /**
     * 获取共享参数
     * @return [type] [description]
     */
    public function callback()
    {
        return [
            'username' => $this->_config['apiUser'],
            'gateway' => 'sendcloud',
            'source' => $this->_config['sender'],
        ];
    }

}
