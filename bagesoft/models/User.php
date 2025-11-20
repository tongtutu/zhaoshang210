<?php

namespace bagesoft\models;
/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id 主键
 * @property string $username 用户名
 * @property string $password 密码
 * @property int $gid 用户组ID
 * @property int $manager_id 经理
 * @property string $email 邮箱
 * @property string $mobile 手机
 * @property string|null $intro 个人说明
 * @property int $state 状态
 * @property string|null $memo 备忘
 * @property int $memo_updated_at 备忘更新时间
 * @property int $login_error 登录失败次数
 * @property int $login_count 登录次数
 * @property string $last_login_ip 最后登录IP
 * @property int $last_login_at 最后登录时间
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class User extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'mobile'], 'required'],
            [['gid', 'manager_id', 'state', 'memo_updated_at', 'login_error', 'login_count', 'last_login_at', 'updated_at', 'created_at'], 'integer'],
            [['intro', 'memo'], 'string'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['last_login_ip'], 'string', 'max' => 15],
            [['username'], 'unique'],
            [['mobile'], 'unique'],
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
            'username' => '用户名',
            'password' => '密码',
            'gid' => '用户组ID',
            'manager_id' => '经理',
            'email' => '邮箱',
            'mobile' => '手机',
            'intro' => '个人说明',
            'state' => '状态',
            'memo' => '备忘',
            'memo_updated_at' => '备忘更新时间',
            'login_error' => '登录失败次数',
            'login_count' => '登录次数',
            'last_login_ip' => '最后登录IP',
            'last_login_at' => '最后登录时间',
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
     * 关联组
     * @return ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(UserGroup::className(), ['id' => 'gid']);
    }

}
