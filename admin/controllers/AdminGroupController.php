<?php
/**
 * 管理员角色
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use Yii;
use yii\data\Pagination;
use bagesoft\library\Tree;
use bagesoft\models\Admin;
use bagesoft\models\AdminMenu;
use bagesoft\models\AdminGroup;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class AdminGroupController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = AdminGroup::find();
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
        $model = new AdminGroup();
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
        if (in_array($id, [1, 2])) {
            throw new ForbiddenHttpException('受保护的组');
        }
        $model = $this->findModel($id);
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
        $model = AdminMenu::find()->asArray()->orderBy('lft ASC')->all();
        $obj = new Tree();
        $obj->init($model);
        $arr = $obj->get_tree_array(1, 'a');
        return $arr;
    }

    /**
     * 删除
     *
     * 被删除组内管理将被移动到禁用组
     */
    public function actionDelete($id)
    {
        parent::acl();
        if (in_array($id, [1, 2])) {
            throw new NotFoundHttpException('受保护的组');
        }
        Admin::updateAll(['id' => 2], 'gid=:gid', ['gid' => $id]);
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = AdminGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
