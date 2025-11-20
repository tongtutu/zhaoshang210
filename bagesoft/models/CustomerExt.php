<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%customer_ext}}".
 *
 * @property int $project_id 项目ID
 * @property int $bt_request 招投标需求
 * @property int $bt_request_respond 招投标需求响应
 * @property int $del_request 删除请求
 * @property int $demand_request 需求请求
 */
class CustomerExt extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_ext}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
            [['project_id', 'bt_request', 'bt_request_respond', 'del_request', 'demand_request'], 'integer'],
            [['project_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => '项目ID',
            'bt_request' => '招投标需求',
            'bt_request_respond' => '招投标需求响应',
            'del_request' => '删除请求',
            'demand_request' => '需求请求',
        ];
    }
}
