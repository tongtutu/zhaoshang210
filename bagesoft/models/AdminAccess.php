<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%admin_access}}".
 *
 * @property int $id 权限ID
 * @property int $uid 所属用户权限ID
 * @property int $gid 所属群组权限ID
 * @property int $menuid 权限模块ID
 */
class AdminAccess extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_access}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'gid', 'menuid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '权限ID',
            'uid' => '所属用户权限ID',
            'gid' => '所属群组权限ID',
            'menuid' => '权限模块ID',
        ];
    }
}
