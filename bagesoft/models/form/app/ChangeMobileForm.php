<?php
namespace bagesoft\models\form\app;

use bagesoft\constant\System;
use bagesoft\functions\HashFunc;
use bagesoft\functions\UserFunc;

class ChangeMobileForm extends \bagesoft\common\models\Base
{
    public $mobile;
    public $mobileOld;
    public $uid;
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
            'mobileOld' => '原手机号',
            'mobile' => '新手机号',
            'code' => '手机验证码',
        ];
    }

    /**
     * 更换手机
     *
     * @return mixed
     */
    public function changeMobile()
    {
        $token = System::HASH_CODE_EDIT_MOBILE . '~' . $this->mobile;
        $hashtest = HashFunc::test(['token' => $token, 'hash' => $this->code]);
        if ($hashtest != 'success') {
            $this->addError('code', $hashtest);
        } else {
            if (UserFunc::getUserByMobile($this->mobile)) {
                $this->addError('mobile', '该手机号已经被注册');
            } else {
                $model = UserFunc::getUserById($this->uid);
                if (false == $model) {
                    $this->addError('mobile', '当前用户信息不存在');
                } else {
                    $model->mobile = $this->mobile;
                    if ($model->save()) {
                        HashFunc::setExpire($token, $this->code);
                        return $model;
                    }
                }
            }
        }
        return false;
    }
}
