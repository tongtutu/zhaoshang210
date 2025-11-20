<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%task}}".
 *
 * @property int $id ID
 * @property string $index_id 索引ID
 * @property string $task_name 执行任务名
 * @property string $act 执行任务动作
 * @property string|null $payload 数据负载
 * @property string|null $result 执行结果
 * @property int $state 执行状态[1-已执行，2-执行中，3-待执行]
 * @property string $remark 备注
 * @property int $updated_at 最后更新
 * @property int $created_at 入库时间
 */
class Task extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payload', 'result'], 'string'],
            [['state', 'updated_at', 'created_at'], 'integer'],
            [['index_id'], 'string', 'max' => 32],
            [['task_name', 'remark'], 'string', 'max' => 255],
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
            'index_id' => '索引ID',
            'task_name' => '执行任务名',
            'act' => '执行任务动作',
            'payload' => '数据负载',
            'result' => '执行结果',
            'state' => '执行状态[1-已执行，2-执行中，3-待执行]',
            'remark' => '备注',
            'updated_at' => '最后更新',
            'created_at' => '入库时间',
        ];
    }
}
