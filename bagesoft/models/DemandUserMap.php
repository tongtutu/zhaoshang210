<?php

namespace bagesoft\models;
/**
 * This is the model class for table "{{%demand_user_map}}".
 *
 * @property int $id ID
 * @property int $uid 用户UID
 * @property int $demand_id 需求ID
 * @property int $role_type 角色类型
 */
class DemandUserMap extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%demand_user_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'demand_id', 'role_type'], 'integer'],
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
            'demand_id' => '需求ID',
            'role_type' => '角色类型',
        ];
    }

    public function getDemand()
    {
        return $this->hasOne(Demand::class, ['id' => 'demand_id']);
    }
}
