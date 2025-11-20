<?php

namespace bagesoft\models;

use bagesoft\constant\System;
/**
 * This is the model class for table "{{%invest_user_map}}".
 *
 * @property int $id ID
 * @property int $uid 用户UID
 * @property int $project_id 客户ID
 * @property int $role_type 角色类型
 */
class InvestUserMap extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invest_user_map}}';
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
}
