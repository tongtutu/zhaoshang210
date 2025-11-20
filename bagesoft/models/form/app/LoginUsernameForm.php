<?php
namespace bagesoft\models\form\app;

use bagesoft\constant\System;
use bagesoft\functions\UserFunc;
use Yii;

class LoginUsernameForm extends \bagesoft\common\models\Base
{
    public $username;
    public $password;
    public $keeplogin;

    // 添加规则验证
    public function rules()
    {
        return [
            [['keeplogin'], 'integer'],
            [['username', 'password'], 'required'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'keeplogin' => '保持登录',
        ];
    }

    /**
     * 登录
     *
     * @return object
     */
    public function login()
    {
        $model = UserFunc::getUserByUsername($this->username);
        if (false == $model) {
            $this->addError('username', '用户不存在');
        } elseif ($model->state == 2) {
            $this->addError('username', '用户被锁定，请联系管理');
        } elseif ($model->login_error >= System::ALLOW_LOGIN_FAIL_NUM) {
            $this->addError('username', '错误次数已达上限，禁止登录!');
        } elseif ($model->password != md5($this->password)) {

            $model->login_error = $model->login_error + 1;
            if ($model->login_error == System::ALLOW_LOGIN_FAIL_NUM) {
                $model->state = 2;
            }
            $model->save();
            $activeCount = System::ALLOW_LOGIN_FAIL_NUM - $model->login_error;
            if ($activeCount > 0) {
                $message = '密码错误，尝试 ' . $activeCount . ' 次后账号锁定';
            } else {
                $message = '密码错误已达上限，禁止登录';
            }
            $this->addError('password', $message);
        } else {
            $model->login_error = 0;
            $model->last_login_ip = Yii::$app->request->userIP;
            $model->last_login_at = time();
            $model->login_count = $model->login_count + 1;
            $model->save();
            return $model;
        }
        return false;
    }
}
