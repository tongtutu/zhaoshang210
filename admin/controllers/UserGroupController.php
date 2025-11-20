<?php
/**
 * 会员组
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use Yii;
use yii\data\Pagination;
use bagesoft\library\Tree;
use bagesoft\models\UserMenu;
use bagesoft\models\UserGroup;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class UserGroupController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = UserGroup::find();
        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
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
        $model = new UserGroup();
        if ($model->load(Yii::$app->request->post())) {
            if (is_array($model->acl)) {
                $model->acl = implode(',', $model->acl);
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render(
            'create',
            [
                'model' => $model,
                'tree' => $this->tree(),
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
        if ($model->id == 1) {
            throw new ForbiddenHttpException('受保护的用户组不能修改');
        }
        if ($model->load(Yii::$app->request->post())) {
            if (is_array($model->acl)) {
                $model->acl = implode(',', $model->acl);
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render(
            'update',
            [
                'model' => $model,
                'tree' => $this->tree(),
            ]
        );
    }

    /**
     * 生成树结构
     * @return [type] [description]
     */
    private function tree()
    {
        $model = UserMenu::find()->asArray()->orderBy('lft ASC')->all();
        $obj = new Tree();
        $obj->init($model);
        $arr = $obj->get_tree_array(1, 'a');
        return $arr;
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        parent::acl();
        if ($id == 1) {
            throw new ForbiddenHttpException('受保护的用户组不能删除');
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = UserGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
