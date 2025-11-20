<?php
/**
 * 主页
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */
namespace app\controllers;

use bagesoft\models\form\app\ChangeMobileForm;
use bagesoft\models\form\app\ChangePasswordForm;
use bagesoft\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class HomeController extends \bagesoft\common\controllers\app\Base
{
    public function beforeAction($action)
    {
        if ($action->id == 'edit-memo') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * 修改密码
     *
     */
    public function actionEditPass()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->uid = $this->session['uid'];
            $model->mobile = $this->user->mobile;
            if ($model->validate()) {
                if ($model->changePassword()) {
                    Yii::$app->session->setFlash('editPass', '密码修改成功');
                    return $this->redirect(['edit-pass']);
                }
            }
        }
        return $this->render(
            'edit-pass',
            [
                'model' => $model,
            ]
        );

    }

    /**
     * 修改手机号
     *
     */
    public function actionEditMobile()
    {
        $model = new ChangeMobileForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->uid = $this->session['uid'];
            if ($model->validate()) {
                if ($model->changeMobile()) {
                    Yii::$app->session->setFlash('editMobile', '手机号修改成功');
                    return $this->redirect(['edit-mobile']);
                }
            }
        }
        return $this->render(
            'edit-mobile',
            [
                'user' => $this->user,
                'model' => $model,
            ]
        );

    }

    /**
     * 备忘更新
     * @return
     */
    public function actionEditMemo()
    {
        try {
            $model = $this->findModel($this->session['uid']);
            if ($model->load(Yii::$app->request->post())) {
                $model->memo_updated_at = time();
                if ($model->validate()) {
                    if ($model->save()) {
                        parent::renderSuccessJson([], '更新完成！' . \date('Y-m-d H:i:s'));
                    } else {
                        throw new \Exception('保存错误');
                    }
                } else {
                    throw new \Exception('验证错误');
                }
            }

        } catch (\Exception $e) {
            parent::renderErrorJson($e->getMessage());
        }
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('记录不存在');
        }
    }
}
