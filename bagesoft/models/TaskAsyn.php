<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%task_asyn}}".
 *
 * @property int $id ID
 * @property string $track_id 跟踪ID
 * @property string $mod 归属模块
 * @property string $task_name 任务名称
 * @property int $data_id 任务数据ID
 * @property string $act 任务标识
 * @property string|null $payload 有效载荷
 * @property string|null $extend_data 扩展数据(发送帐户密码)
 * @property int $is_exec 执行状态
 * @property int $done_at 完成时间
 * @property string|null $result 执行结果
 * @property int $day_at 日
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class TaskAsyn extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task_asyn}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_id', 'is_exec', 'done_at', 'day_at', 'updated_at', 'created_at'], 'integer'],
            [['payload', 'extend_data', 'result'], 'string'],
            [['track_id'], 'string', 'max' => 36],
            [['mod'], 'string', 'max' => 20],
            [['task_name'], 'string', 'max' => 255],
            [['act'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track_id' => '跟踪ID',
            'mod' => '归属模块',
            'task_name' => '任务名称',
            'data_id' => '任务数据ID',
            'act' => '任务标识',
            'payload' => '有效载荷',
            'extend_data' => '扩展数据(发送帐户密码)',
            'is_exec' => '执行状态',
            'done_at' => '完成时间',
            'result' => '执行结果',
            'day_at' => '日',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
