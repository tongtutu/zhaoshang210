<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%admin_group}}".
 *
 * @property int $id 主键
 * @property string $group_name 组名称
 * @property string $acl 权限
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class AdminGroup extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_name', 'acl'], 'required'],
            [['acl'], 'string'],
            [['updated_at', 'created_at'], 'integer'],
            [['group_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'group_name' => '组名称',
            'acl' => '权限',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
