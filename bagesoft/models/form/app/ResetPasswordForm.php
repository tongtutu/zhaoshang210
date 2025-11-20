<?php
namespace bagesoft\models\form\app;

use bagesoft\constant\System;
use bagesoft\functions\HashFunc;
use bagesoft\functions\UserFunc;
use bagesoft\helpers\Utils;
use Yii;

class ResetPasswordForm extends \bagesoft\common\models\Base
{
    public $mobile;
    public $code;

    // 添加规则验证
    public function rules()
    {
        return [
            [['mobile', 'code'], 'required'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'code' => '验证码',
        ];
    }

    public function reset()
    {
        $token = System::HASH_CODE_RESET_PASS . '~' . $this->mobile;
        $hashtest = HashFunc::test(['token' => $token, 'hash' => $this->code]);
        $passowrd = Utils::randStr(10);
        if ($hashtest != 'success') {
            $this->addError('code', $hashtest);
        } else {
            $model = UserFunc::getUserByMobile($this->mobile);
            if (false == $model) {
                $this->addError('mobile', '手机号未注册');
            } elseif ($model->state == 2) {
                $this->addError('mobile', '用户被锁定，请联系管理');
            } elseif ($model->login_error >= System::ALLOW_LOGIN_FAIL_NUM) {
                $this->addError('mobile', '错误次数已达上限，禁止重置!');
            } else {
                $model->password = md5($passowrd);
                $model->login_error = 0;
                $model->last_login_ip = Yii::$app->request->userIP;
                $model->last_login_at = time();
                $model->login_count = $model->login_count + 1;
                $model->save();
                HashFunc::setExpire($token, $this->code);
                Yii::$app->session->setFlash('resetPassword', '密码重置成功：' . $passowrd);
            }
        }
        return false;
    }
}
