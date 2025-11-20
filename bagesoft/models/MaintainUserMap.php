<?php

namespace bagesoft\models;
/**
 * This is the model class for table "{{%maintain_user_map}}".
 *
 * @property int $id ID
 * @property int $uid 用户UID
 * @property int $maintain_id 跟进ID
 * @property int $state 状态ID
 * @property int $role_type 角色类型
 */
class MaintainUserMap extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%maintain_user_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'maintain_id', 'state', 'role_type'], 'integer'],
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
            'maintain_id' => '跟进ID',
            'state' => '状态ID',
            'role_type' => '角色类型',
        ];
    }

    public function getMaintain()
    {
        return $this->hasOne(Maintain::class, ['id' => 'maintain_id']);
    }
}
