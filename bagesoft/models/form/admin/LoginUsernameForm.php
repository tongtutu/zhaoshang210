<?php
namespace bagesoft\models\form\admin;

use bagesoft\constant\UserConst;
use bagesoft\constant\System;
use bagesoft\functions\AdminFunc;
use bagesoft\models\Admin;
use Yii;
use yii\helpers\Html;

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
        $admin = Admin::find()->where('username=:username', ['username' => $this->username])->one();
        if (false == $admin) {
            $this->addError('username', '用户不存在');
            AdminFunc::log(['intro' => '用户不存在：' . Html::encode($this->username)]);
        } elseif ($admin->state == UserConst::STATUS_LOCK) {
            $this->addError('username', '用户被锁定，请联系管理');
            AdminFunc::log(['intro' => '用户被锁定：' . Html::encode($this->username)]);
            $this->addError('_message', 'lock');
        } elseif ($admin->login_error >= System::ALLOW_LOGIN_FAIL_NUM) {
            $this->addError('username', '错误次数已达上限，禁止登录!');
            AdminFunc::log(['intro' => '错误次数已达上限：' . Html::encode($this->username)]);
            $this->addError('_message', 'lock');
        } elseif ($admin->password != md5($this->password)) {
            $admin->login_error = $admin->login_error + 1;
            if ($admin->login_error == System::ALLOW_LOGIN_FAIL_NUM) {
                $admin->state = UserConst::STATUS_LOCK;
            }
            $admin->save();
            $activeCount = System::ALLOW_LOGIN_FAIL_NUM - $admin->login_error;
            if ($activeCount > 0) {
                $message = '密码错误，尝试 ' . $activeCount . ' 次后账号锁定';
            } else {
                $message = '密码错误已达上限，禁止登录';
            }
            AdminFunc::log(['intro' => $message . '：' . Html::encode($this->username)]);
            $this->addError('password', $message);
        } else {
            $admin->login_error = 0;
            $admin->last_login_ip = Yii::$app->request->userIP;
            $admin->last_login_at = time();
            $admin->login_count = $admin->login_count + 1;
            $admin->save();
            AdminFunc::log(['uid' => $admin->id, 'username' => $admin->username, 'intro' => '登录成功：' . Html::encode($this->username)]);
            return $admin;
        }
        return false;
    }
}
