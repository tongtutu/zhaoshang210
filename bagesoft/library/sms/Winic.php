<?php
/**
 * 吉信通
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\library\sms;

use bagesoft\helpers\Utils;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class Winic extends BaseObject
{
    private $_error = '';
    private $_config = [];
    private $_url = 'http://service.winic.org/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=';
    public $config = [];
    public $params = '';
    private $_errorNo = [
        '000' => '发送成功',
        '-01' => '当前账号余额不足',
        '-02' => '当前用户ID错误',
        '-03' => '当前密码错误',
        '-04' => '当前账参数不够或参数内容的类型错误余额不足',
        '-05' => '当前账号余额手机号码格式不对',
        '-06' => '短信内容编码不对',
        '-07' => '短信内容含有敏感字符',
        '-08' => '无接收数据',
        '-09' => '系统维护中',
        '-10' => '手机号码数量超长！（100个/次 超100个请自行做循环）',
        '-11' => '短信内容超长！（70个字符）',
        '-12' => '其它错误！',
    ];

    /**
     * 构造函数
     * @return
     */
    public function init()
    {
        $this->_config = ArrayHelper::merge($this->_config, Yii::$app->params['sms.winic']);

        if (is_array($this->config)) {
            $this->_config = ArrayHelper::merge($this->_config, $this->config);
        }
    }

    /**
     * 发送信息
     */
    public function send(array $vars)
    {
        $target = urlencode($vars['target']);
        $content = urlencode(Utils::autoCharset($vars['content'], 'utf-8', 'gbk'));
        $rurl = sprintf($this->_url, $this->_config['username'], $this->_config['password'], $target, $content);
        $ret = file($rurl);
        $result = explode('/', $ret[0]);
        $return = $result[0];
        if ($return < '0') {
            $this->_error = $return;
            return $this->_errorNo[$return];
        } else {
            return [
                'state' => 'success',
                'code' => $return,
                'message' => $this->_errorNo[$return],
                'params' => $result,
            ];
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
            'gateway' => 'winic',
        ];
    }

    /**
     * 获取错误
     * @param string $errorNo
     * @return multitype:string |string
     */
    public function getError($errorNo = null)
    {
        if ($errorNo && isset($this->_errorNo[$errorNo])) {
            return $this->_errorNo[$errorNo];
        } elseif ($this->_error && isset($this->_errorNo[$this->_error])) {
            return $this->_errorNo[$this->_error] . '( ' . $this->_error . ' )';
        } elseif ($this->_error) {
            return '其它错误。( ' . $this->_error . ' )';
        } else {
            return false;
        }
    }

}
