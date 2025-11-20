<?php
/**
 * Amqp基础类
 *
 * @author        shuguang <5565907@qq.com>
 *
 * Exchange
 * ::setType:AMQP_EX_TYPE_DIRECT、 AMQP_EX_TYPE_FANOUT、AMQP_EX_TYPE_HEADERS 、 AMQP_EX_TYPE_TOPIC
 *
 * ::setFlags:AMQP_NOPARAM, AMQP_DURABLE, AMQP_PASSIVE、AMQP_AUTODELETE
 *
 */

namespace bagesoft\library;

use Yii;

class PhpAmqp
{

    public $AMQPChannel;

    public $AMQPConnection;

    public $AMQPEnvelope;

    public $AMQPExchange;

    public $AMQPQueue;

    public $config;

    public $exchange;

    /**
     * Construct
     * @throws \AMQPConnectionException
     */
    public function __construct($config = null)
    {
        if (null == $config) {
            $config = Yii::$app->params['mq'];
        }
        $this->config = $config['server'];
        $this->exchange = $config['exchange'];
        $this->AMQPConnection = new \AMQPConnection($this->config);
        $this->AMQPConnection->connect();
        // if (!$this->AMQPConnection->connect()) {
        //     throw new \AMQPConnectionException("Cannot connect to the broker!\n");
        // }
        return;
    }

    /**
     * Channel
     * @return \AMQPChannel
     * @throws \AMQPConnectionException
     */
    public function channel()
    {
        if (!$this->AMQPChannel) {
            $this->AMQPChannel = new \AMQPChannel($this->AMQPConnection);
        }
        return $this->AMQPChannel;
    }

    /**
     * Exchange
     * @return \AMQPExchange
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     */
    public function exchange()
    {
        if (!$this->AMQPExchange) {
            $this->AMQPExchange = new \AMQPExchange($this->channel());
            $this->AMQPExchange->setName($this->exchange);
        }
        return $this->AMQPExchange;
    }

    /**
     * Queue
     * @return \AMQPQueue
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function queue()
    {
        if (!$this->AMQPQueue) {
            $this->AMQPQueue = new \AMQPQueue($this->channel());
        }
        return $this->AMQPQueue;
    }

    /**
     * Envelope
     * @return \AMQPEnvelope
     */
    public function envelope()
    {
        if (!$this->AMQPEnvelope) {
            $this->AMQPEnvelope = new \AMQPEnvelope();
        }
        return $this->AMQPEnvelope;
    }

    /**
     * Close
     */
    public function close()
    {
        $this->AMQPConnection->disconnect();
    }
}
