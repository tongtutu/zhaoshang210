<?php
/**
 * 推送模板
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\models\SendTpl;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class SendTplController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = SendTpl::find();
        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '50', 'defaultPageSize' => 50]);
        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('title_alias ,id DESC')->all();
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
        parent::acl();
        $model = new SendTpl();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $model->loadDefaultValues();
            return $this->render('create',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        parent::acl();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        parent::acl();
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = SendTpl::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
