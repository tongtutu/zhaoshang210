<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%hash_counter}}".
 *
 * @property int $id ID
 * @property string $target 对象
 * @property int $error_count 错误次数
 * @property int $expire_at 过期时间
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class HashCounter extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hash_counter}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['error_count', 'expire_at', 'updated_at', 'created_at'], 'integer'],
            [['target'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target' => '对象',
            'error_count' => '错误次数',
            'expire_at' => '过期时间',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
