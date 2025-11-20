<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%message}}".
 *
 * @property int $id ID
 * @property int $uid 用户UID
 * @property string $title 主题
 * @property string|null $content 内容
 * @property int $isread 阅读状态
 * @property int $read_at 阅读时间
 * @property int $is_send 是否发送
 * @property int|null $send_at 发送时间
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class Message extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'isread', 'read_at', 'is_send', 'send_at', 'updated_at', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 100],
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
            'title' => '主题',
            'content' => '内容',
            'isread' => '阅读状态',
            'read_at' => '阅读时间',
            'is_send' => '是否发送',
            'send_at' => '发送时间',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
