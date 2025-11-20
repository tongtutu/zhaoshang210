<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%admin_log}}".
 *
 * @property int $id 主键
 * @property string $method 方法
 * @property int $uid 用户ID
 * @property string $username 用户名
 * @property string $action 操作
 * @property string|null $action_url 操作URL
 * @property string $ip IP
 * @property string|null $intro 描述
 * @property string|null $datas 参数
 * @property string|null $user_agent 用户代理
 * @property string $date_at 操作日期
 * @property int $created_at 入库时间
 */
class AdminLog extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['method', 'action_url', 'intro', 'datas', 'user_agent'], 'string'],
            [['uid', 'created_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['action'], 'string', 'max' => 32],
            [['ip'], 'string', 'max' => 15],
            [['date_at'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'method' => '方法',
            'uid' => '用户ID',
            'username' => '用户名',
            'action' => '操作',
            'action_url' => '操作URL',
            'ip' => 'IP',
            'intro' => '描述',
            'datas' => '参数',
            'user_agent' => '用户代理',
            'date_at' => '操作日期',
            'created_at' => '入库时间',
        ];
    }
}
