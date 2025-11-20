<?php

namespace bagesoft\models;
/**
 * This is the model class for table "{{%customer_user_map}}".
 *
 * @property int $id ID
 * @property int $uid 用户UID
 * @property int $project_id 客户ID
 * @property int $role_type 角色类型
 */
class CustomerUserMap extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_user_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'project_id', 'role_type'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户UID',
            'project_id' => '客户ID',
            'role_type' => '角色类型',
        ];
    }
    
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'project_id']);
    }
}
