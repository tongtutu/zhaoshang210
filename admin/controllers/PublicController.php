<?php
/**
 * 公共
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\constant\System;
use bagesoft\functions\AdminFunc;
use bagesoft\models\Admin;
use bagesoft\models\form\admin\LoginUsernameForm;
use Yii;
use yii\helpers\Url;

class PublicController extends \bagesoft\common\controllers\Base
{
    public $layout = 'login';
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 首页
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $this->redirect(Url::toRoute(['public/login']));
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
                parent::_sessionSet(
                    'admin',
                    [
                        'uid' => $login->id,
                        'gid' => $login->gid,
                        'username' => $login->username,
                        'role' => System::USER_ROLE_ADMIN,
                    ]
                );
                if ($model->keeplogin == 1) {
                    parent::_cookiesSet(
                        [
                            'name' => 'admin',
                            'value' => $login->id,
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
     * !!!debug!!!
     *
     */
    // public function actionSlogin($id)
    // {

    //     $login = AdminFunc::getItemById($id);

    //     if ($login) {
    //         parent::_sessionSet(
    //             'admin',
    //             [
    //                 'uid' => $login->id,
    //                 'gid' => $login->gid,
    //                 'username' => $login->username,
    //                 'role' => System::USER_ROLE_ADMIN,
    //             ]
    //         );

    //         $this->redirect(['site/index']);
    //     }

    // }

    /**
     * 退出登录
     */
    public function actionLogout()
    {
        parent::_sessionRemove('admin');
        parent::_cookiesRemove('admin');
        $this->redirect(['public/login']);
    }
}
