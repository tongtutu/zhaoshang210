<?php
/**
 * 节点通信基类
 * @author: shuguang <5565907@qq.com>
 * @date: 2020/11/09 14:35:05
 * @lastEditTime: 2025/01/01 11:47:02
 */

namespace bagesoft\communication\base;

use bagesoft\constant\MessageQueueConst;
use bagesoft\functions\system\MqFunc;
use bagesoft\helpers\Utils;
use bagesoft\models\Task;
use yii\helpers\ArrayHelper;

class DccommBase
{
    protected $nodeName; //节点服务器名
    protected $exchangeName; //交换机名
    protected $queueName; //队列名
    protected $routeName; //路由名
    protected $payloadAppend = []; //负载追加不定参数
    protected $payloadIgnore = ['taskName']; //踢除负载无关参数

    /**
     * @param array ...$data
     * $data[0]:基础数据
     * $data[1]:phpAMQP参数
     */
    public function __construct(...$data)
    {
        if ($data[0] && is_array($data[0])) {
            foreach ($data[0] as $key => $row) {
                $this->$key = $row;
                if (!is_object($this->$key) && !in_array($key, $this->payloadIgnore)) {
                    $this->payloadAppend[$key] = $row;
                }
            }
        }
        $mqArgs = $data[1];
        $this->nodeName = $mqArgs['nodeName'] ?: MessageQueueConst::WEB_TO_DC;
        $this->exchangeName = $mqArgs['exchangeName'] ?: MessageQueueConst::DC_ASYN_EXCHANGE_NAME;
        $this->queueName = $mqArgs['queueName'] ?: MessageQueueConst::DC_ASYN_QUEUE_NAME;
        $this->routeName = $mqArgs['routeName'] ?: MessageQueueConst::DC_ASYN_ROUTE_NAME;
    }

    /**
     * getName
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * set value
     *
     * @param string $name
     * @param mixed $val
     */
    public function __set($name, $val)
    {
        $this->$name = $val;
    }

    /**
     * 推送消息
     * @param  array $data 消息内容
     */
    public function publish($data)
    {
        if (!is_array($data)) {
            throw new \Exception('缺少参数 [Array]');
        }
        $message = $this->getMessage($data);

        try {
            Task::addNew(
                [
                    'index_id' => (string) $message['indexId'],
                    'task_name' => $this->taskName ? $this->taskName : $this->act,
                    'act' => $this->act,
                    'payload' => Utils::jsonStr($message['payload']),
                ]
            );
            //DEBUG...
            //Utils::dump($message);
            $encode = Utils::jsonStr($message);
            $publish = (new MqFunc($this->nodeName))->publish(
                $encode, $this->queueName,
                [
                    'route' => $this->routeName, 'exchange' => $this->exchangeName,
                ]
            );
            if ($publish) {
                return [
                    'result' => 'success',
                    'message' => '提交完成',
                    'data' => [
                        'indexId' => $message['indexId'],
                    ],
                ];
            } else {
                throw new \Exception($publish);
            }
        } catch (\Throwable $th) {
            // Utils::dump($th->getMessage());
            return $th->getMessage();
        }
    }

    /**
     * 构建消息体
     *
     * @param array $data
     * @return array
     */
    private function getMessage($data)
    {
        $message = [
            'mod' => 'dccomm',
            'act' => $this->act,
            'indexId' => $this->indexId ?: Utils::indexId(),
            'args' => $this->args ?: [],
        ];

        if (isset($data['payload'])) {
            $message['payload'] = $data['payload'];
        } else {
            unset($this->payloadAppend['act']); //删除重复元素
            $message['payload'] = $data;
            $message['payload'] = ArrayHelper::merge($this->payloadAppend, $message['payload']);
        }
        return $message;
    }
}
