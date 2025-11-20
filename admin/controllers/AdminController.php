<?php
/**
 * 管理员
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\models\Admin;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class AdminController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        parent::acl();

        $model = Admin::find();
        $count = $model->count();
        $model->alias("admin");
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $model->joinWith('group group');
        $model->alias('admin');
        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all();
        return $this->render(
            'index',
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
        $model = new Admin();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        }

        return $this->render(
            'create',
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
        parent::acl();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $oldAttr = $model->oldAttributes;
            if (empty($model->password)) {
                $model->password = $oldAttr['password'];
            } else {
                $model->password = md5($model->password);
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        $model->password = '';
        return $this->render(
            'update',
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
        parent::acl();
        if ($id == 1) {
            throw new NotFoundHttpException('系统调试用户不能删除！');
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
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
