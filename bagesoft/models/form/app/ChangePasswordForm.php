<?php
namespace bagesoft\models\form\app;

use bagesoft\constant\System;
use bagesoft\functions\HashFunc;
use bagesoft\functions\UserFunc;

class ChangePasswordForm extends \bagesoft\common\models\Base
{
    public $password;
    public $mobile;
    public $repassword;
    public $uid;
    public $code;

    // 添加规则验证
    public function rules()
    {
        return [
            [['mobile'], 'string', 'max' => 30],
            [['password', 'repassword', 'code'], 'required'],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不匹配'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => '新密码',
            'repassword' => '确认密码',
            'code' => '手机验证码',
        ];
    }

    /**
     * 修改密码
     *
     * @return mixed
     */
    public function changePassword()
    {
        $token = System::HASH_CODE_EDIT_PASS . '~' . $this->mobile;
        $code = $this->code;
        $hashtest = HashFunc::test(['token' => $token, 'hash' => $code]);
        if ($hashtest != 'success') {
            $this->addError('code', $hashtest);
        } else {
            $model = UserFunc::getUserById($this->uid);
            if (false == $model) {
                $this->addError('password', '用户查询失败');
            } else {
                $model->password = md5($this->password);
                if ($model->save()) {
                    HashFunc::setExpire($token, $code);
                    return $model;
                }
            }
        }
        return false;
    }
}
