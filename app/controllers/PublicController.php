<?php
/**
 * 公共
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use bagesoft\constant\System;
use bagesoft\functions\UserFunc;
use bagesoft\models\form\app\LoginMobileForm;
use bagesoft\models\form\app\LoginUsernameForm;
use bagesoft\models\form\app\ResetPasswordForm;
use Yii;
use yii\helpers\Url;

class PublicController extends \bagesoft\common\controllers\Base
{
    public $layout = 'login';
    public function actions()
    {
        return [
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 首页
     */
    public function actionIndex()
    {
        $this->redirect(Url::toRoute(['public/login']));
    }

    /**
     * 手机验证码登录
     *
     */
    public function actionMobile()
    {
        $model = new LoginMobileForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $login = $model->login();

            if ($login) {
                parent::_sessionSet('zhaoshang',
                    [
                        'uid'      => $login->id,
                        'gid'      => $login->gid,
                        'username' => $login->username,
                    ]
                );
                if ($model->keeplogin == 1) {
                    parent::_cookiesSet(
                        [
                            'name'   => 'zhaoshang',
                            'value'  => $login->id,
                            'expire' => time() + 604800,
                        ]
                    );
                }
                $this->redirect(['site/index']);
            }

        }
        return $this->render('mobile', [
            'model' => $model,
        ]);
    }

    /**
     * 找回密码
     */
    public function actionResetPassword()
    {
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->reset();
        }
        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * 登录
     */
    public function actionLogin()
    {
        $model = new LoginUsernameForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $login = $model->login();

            if ($login) {

                parent::_sessionSet('zhaoshang',
                    [
                        'uid'      => $login->id,
                        'gid'      => $login->gid,
                        'username' => $login->username,
                        'role'     => System::USER_ROLE_APP,
                    ]
                );
                if ($model->keeplogin == 1) {
                    parent::_cookiesSet(
                        [
                            'name'   => 'zhaoshang',
                            'value'  => $login->id,
                            'expire' => time() + 604800,
                        ]
                    );
                }
                $this->redirect(['site/index']);
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * 
     *
     */
    public function actionSlogin($id)
    {
        $login = UserFunc::getUserById($id);

        if ($login) {
            parent::_sessionSet('zhaoshang',
                [
                    'uid'      => $login->id,
                    'gid'      => $login->gid,
                    'username' => $login->username,
                ]
            );

            $this->redirect(['site/index']);
        }

    }

    /**
     * 退出登录
     */
    public function actionLogout()
    {
        parent::_sessionRemove('zhaoshang');
        parent::_cookiesRemove('zhaoshang');
        $this->redirect(['public/login']);
    }
}
