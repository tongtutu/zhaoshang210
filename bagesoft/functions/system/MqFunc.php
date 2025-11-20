<?php
/**
 * AMQP 消息推送
 * @author: shuguang < 5565907@qq.com >
 * @date: 2022/01/06 13:36:38
 * @lastEditTime: 2024/12/31 16:34:50
 */

namespace bagesoft\functions\system;

use bagesoft\constant\MessageQueueConst;
use bagesoft\helpers\Utils;
use bagesoft\library\PhpAmqp;
use Yii;

class MqFunc
{
    protected $config;
    public function __construct($nodeName = 'dcServer')
    {
        if (isset(Yii::$app->params['mq'][$nodeName]) && is_array(Yii::$app->params['mq'][$nodeName])) {
            $this->config = Yii::$app->params['mq'][$nodeName];
        } else {
            throw new \Exception('node info not exists.');
        }
    }

    /**
     * 由WEB向节点推送数据
     *
     * @param  string $msg    消息内容
     * @param  array  $routes 路由
     * @param  array  $args   参数
     */
    public function publish($message, $queue = '', $args = ['route' => null, 'exchange' => null])
    {
        //格式化MSG
        if (is_array($message)) {
            $message = Utils::jsonStr($message);
        }
        //实例化AMQP
        $amqp = new PhpAmqp($this->config);
        //创建交换机对象
        $ex = $amqp->exchange();
        $ex->setName($args['exchange'] ?? MessageQueueConst::DC_ASYN_EXCHANGE_NAME);
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        $ex->setFlags(AMQP_DURABLE);
        $ex->declareExchange();
        //创建队列
        $q = $amqp->queue();
        $q->setName($queue ?? MessageQueueConst::DC_ASYN_QUEUE_NAME);
        $q->setFlags(AMQP_DURABLE); //持久化
        $q->declareQueue();
        //绑定交换机与队列，并指定路由键
        $routeKey = $args['route'] ?? $queue;
        $q->bind($ex->getName(), $routeKey);
        $publish = $ex->publish($message, $routeKey, AMQP_NOPARAM, ['delivery_mode' => 2]);
        $amqp->close();
        return $publish;
    }

}
