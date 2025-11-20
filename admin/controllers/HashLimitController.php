<?php
/**
 * 校验码黑名单
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\models\HashLimit;
use Yii;
use yii\data\Pagination;

class HashLimitController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = HashLimit::find();
        $request = Yii::$app->request;
        $target = trim($request->get('target'));
        $typeis = intval($request->get('typeis'));
        if ($target) {
            $model->andWhere('target=:target', ['target' => $target]);
        }
        if ($typeis) {
            $model->andWhere('typeis=:typeis', ['typeis' => $typeis]);
        }
        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all();
        return $this->render('index',
            [
                'count' => $count,
                'pagination' => $pagination,
                'datalist' => $datalist,
            ]
        );
    }

    /**
     * 录入
     */
    public function actionCreate()
    {
        $model = new HashLimit();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        $model->loadDefaultValues();
        $model->expired_at = date('Y-m-d H:i:s');
        return $this->render('create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        $model->expired_at = date('Y-m-d H:i:s', $model->expired_at);
        return $this->render('update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = HashLimit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('该请求无法被执行');
        }
    }
}
