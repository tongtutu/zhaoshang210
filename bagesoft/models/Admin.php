<?php

namespace bagesoft\models;

use bagesoft\models\AdminGroup;
/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id 主键
 * @property string $username 姓名
 * @property string $password 密码
 * @property string $mobile 手机
 * @property string $email 邮箱
 * @property int $gid 用户组
 * @property string|null $memo 备忘
 * @property int $memo_updated_at 备忘更新时间
 * @property int $login_count 登录次数
 * @property string $last_login_ip 最后登录ip
 * @property int $last_login_at 最后登录时间
 * @property int $login_error 登录失败次数
 * @property int $state 状态
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class Admin extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'mobile'], 'required'],
            [['gid', 'memo_updated_at', 'login_count', 'last_login_at', 'login_error', 'state', 'updated_at', 'created_at'], 'integer'],
            [['memo'], 'string'],
            [['username'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['last_login_ip'], 'string', 'max' => 15],
            [['mobile'], 'unique'],
            [['username'], 'unique'],
            ['mobile', function ($attribute, $params) {
                if (!preg_match('/^1[3456789]\d{9}$/', $this->$attribute)) {
                    $this->addError($attribute, '不合法的手机号码.');
                }
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'username' => '姓名',
            'password' => '密码',
            'mobile' => '手机',
            'email' => '邮箱',
            'gid' => '用户组',
            'memo' => '备忘',
            'memo_updated_at' => '备忘更新时间',
            'login_count' => '登录次数',
            'last_login_ip' => '最后登录ip',
            'last_login_at' => '最后登录时间',
            'login_error' => '登录失败次数',
            'state' => '状态',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }

    /**
     * 存前操作
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $oldAttr = $this->oldAttributes;
            //由锁定转正常时还原失败次数
            if (!$insert && $oldAttr['state'] == 2 && $this->state == 1) {
                $this->login_error = 0;
            }
            //密码处理
            if ($insert && $this->password) {
                $this->password = md5($this->password);
            }
            $this->updated_at = time();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 关联角色
     * @return ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(AdminGroup::className(), ['id' => 'gid']);
    }
}
