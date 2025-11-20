<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%task_data}}".
 *
 * @property int $id ID
 * @property string|null $payload 有效载荷
 * @property int $day_at 日
 * @property int $updated_at 最新更新
 * @property int $created_at 入库时间
 */
class TaskData extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payload'], 'string'],
            [['day_at', 'updated_at', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payload' => '有效载荷',
            'day_at' => '日',
            'updated_at' => '最新更新',
            'created_at' => '入库时间',
        ];
    }
}
