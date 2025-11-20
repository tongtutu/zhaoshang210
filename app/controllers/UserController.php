<?php
/**
 * 会员
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use bagesoft\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class UserController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = User::find();
        $request = Yii::$app->request;
        $username = trim($request->get('username'));
        $email = trim($request->get('email'));
        if ($username) {
            $model->andWhere('username=:username', ['username' => $username]);
        }
        if ($email) {
            $model->andWhere('email=:email', ['email' => $email]);
        }
        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $datalist = $model->joinWith('group', false)->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all();
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
        $model = new User();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
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
        parent::acl();
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
