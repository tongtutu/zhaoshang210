<?php
/**
 * 主页
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */
namespace admin\controllers;

use Yii;
use bagesoft\models\Admin;
use bagesoft\helpers\Utils;
use yii\web\NotFoundHttpException;

class HomeController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 备忘更新
     * @return
     */
    public function actionRenewMemo()
    {
        try {
            $memo = trim(Yii::$app->request->post('memo'));
            $model = $this->findModel($this->session['uid']);
            if (false == $model) {
                throw new \Exception('用户不存在');
            }
            $model->memo = $memo;
            $model->memo_updated_at = time();
            
            if (!$model->save()) {
                throw new \Exception('更新失败');
            }
            parent::_renderJson(
                [
                    'code' => 200,
                    'message' => '更新成功',
                ]
            );
        } catch (\Exception $e) {
            parent::_renderJson(
                [
                    'code' => -200,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * 更新资料
     * @return
     */
    public function actionData()
    {
        parent::acl();
        //$model = $this->findModel($this->session['uid']);
        $model = $this->admin;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $oldAttr = $model->oldAttributes;
            if (empty($model->password)) {
                $model->password = $oldAttr['password'];
            } else {
                $model->password = md5($model->password);
            }
            if ($model->save()) {
                return $this->redirect(['home/data']);
            }
        }
        $model->password = '';
        return $this->render('data',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('记录不存在');
        }
    }
}
