<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%task_sys}}".
 *
 * @property int $id ID
 * @property string $track_id 跟踪ID
 * @property string $mod 归属模块
 * @property string $task_name 任务名称
 * @property string $act 任务标识
 * @property string|null $payload 数据体
 * @property int $is_exec 执行状态
 * @property int $cron_at 计划时间
 * @property int $done_at 完成时间
 * @property string|null $result 执行结果
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class TaskSys extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task_sys}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payload', 'result'], 'string'],
            [['is_exec', 'cron_at', 'done_at', 'updated_at', 'created_at'], 'integer'],
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
            'act' => '任务标识',
            'payload' => '数据体',
            'is_exec' => '执行状态',
            'cron_at' => '计划时间',
            'done_at' => '完成时间',
            'result' => '执行结果',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
