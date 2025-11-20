<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%hash}}".
 *
 * @property int $id ID
 * @property string $token TOKEN
 * @property string $target 目标
 * @property string $hash HASH
 * @property string|null $args 参数
 * @property int $used_at 使用时间
 * @property int $state 状态
 * @property int $expire_at 过期时间
 * @property string $ip ip
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class Hash extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hash}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['args'], 'string'],
            [['used_at', 'state', 'expire_at', 'updated_at', 'created_at'], 'integer'],
            [['token', 'hash'], 'string', 'max' => 50],
            [['target'], 'string', 'max' => 80],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'TOKEN',
            'target' => '目标',
            'hash' => 'HASH',
            'args' => '参数',
            'used_at' => '使用时间',
            'state' => '状态',
            'expire_at' => '过期时间',
            'ip' => 'ip',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
