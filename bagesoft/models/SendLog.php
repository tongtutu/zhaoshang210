<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%send_log}}".
 *
 * @property int $id 主键
 * @property int $channel 发送渠道
 * @property string $track_id 跟踪ID
 * @property string $title 标题
 * @property string $act 动作
 * @property string $acc 发送帐户
 * @property string $gateway 网关
 * @property string $target 目标
 * @property string|null $content 内容
 * @property string|null $callback 回调结果
 * @property int $state 状态
 * @property int $date_at 日期
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class SendLog extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%send_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel', 'state', 'date_at', 'updated_at', 'created_at'], 'integer'],
            [['content', 'callback'], 'string'],
            [['track_id'], 'string', 'max' => 36],
            [['title', 'target'], 'string', 'max' => 100],
            [['act', 'acc', 'gateway'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'channel' => '发送渠道',
            'track_id' => '跟踪ID',
            'title' => '标题',
            'act' => '动作',
            'acc' => '发送帐户',
            'gateway' => '网关',
            'target' => '目标',
            'content' => '内容',
            'callback' => '回调结果',
            'state' => '状态',
            'date_at' => '日期',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
