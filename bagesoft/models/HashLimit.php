<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%hash_limit}}".
 *
 * @property int $id ID
 * @property int $typed 限定类型
 * @property string $target 限制对象
 * @property int $cs_uid 客服ID
 * @property string $cs_name 客服姓名
 * @property string|null $intro 备注
 * @property int $expired_at 解锁时间
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class HashLimit extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hash_limit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['typed', 'cs_uid', 'expired_at', 'updated_at', 'created_at'], 'integer'],
            [['target'], 'required'],
            [['intro'], 'string'],
            [['target'], 'string', 'max' => 64],
            [['cs_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typed' => '限定类型',
            'target' => '限制对象',
            'cs_uid' => '客服ID',
            'cs_name' => '客服姓名',
            'intro' => '备注',
            'expired_at' => '解锁时间',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
