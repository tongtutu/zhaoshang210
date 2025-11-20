<?php
/**
 * 后台栏目
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */
namespace admin\controllers;

use bagesoft\models\AdminMenu;
use Yii;
use yii\web\NotFoundHttpException;

class AdminMenuController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = AdminMenu::find()->orderBy('lft ASC')->all();
        //两个循环而已，没有递归
        $parent = [];
        $datalist = [];
        foreach ($model as $row) {
            if (count($parent)) {
                while (count($parent) - 1 > 0 && $parent[count($parent) - 1]['rgt'] < $row['rgt']) {
                    array_pop($parent);
                }
            }
            $row['depath'] = count($parent);
            $parent[] = $row;
            $datalist[] = $row;
        }
        return $this->render('index',
            [
                'datalist' => $datalist,
                'model' => $model,
            ]
        );
    }

    /**
     * 录入
     */
    public function actionCreate()
    {
        parent::acl();
        $model = new AdminMenu();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent_id == 0) {
                $model->saveNode();
            } else if ($model->parent_id) {
                $root = $this->findModel($model->parent_id);
                $model->appendTo($root);
            }
            AdminMenu::cached();
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
     * @param  integer $id id号
     * @return mixed
     */
    public function actionUpdate($id)
    {
        parent::acl();
        if ($id == 1) {
            throw new NotFoundHttpException('根目录禁止操作');
        }
        $model = $this->findModel($id);
        $parent = $model->parent()->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->saveNode();
            if ($model->parent_id == 0 && !$model->isRoot()) {
                $model->moveAsRoot();
            } elseif ($model->parent_id != 0 && $model->parent_id != $parent->id) {
                $root = $this->findModel($model->parent_id);
                $model->moveAsLast($root);
            }
            AdminMenu::cached();
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
     * 移动
     * @param string $id
     * @param string $updown
     * @return mixed
     */
    public function actionMove()
    {
        parent::acl('admin-menu/update');
        $id = intval(Yii::$app->request->get('id'));
        $type = trim(Yii::$app->request->get('type'));
        if (empty($id)) {
            throw new NotFoundHttpException('ID必须提交');
        } elseif ($id == 1) {
            throw new NotFoundHttpException('根目录禁止操作');
        } elseif (empty($type)) {
            throw new NotFoundHttpException('类型必须提交');
        }

        $model = $this->findModel($id);
        if ($type == "down") {
            $sibling = $model->next()->one();
            if (isset($sibling)) {
                $model->moveAfter($sibling);
            }
        } elseif ($type == "up") {
            $sibling = $model->prev()->one();
            if (isset($sibling)) {
                $model->moveBefore($sibling);
            }
        }
        AdminMenu::cached();
        return $this->redirect(['index']);
    }

    /**
     * 状态变更
     * @return mixed
     */
    public function actionState()
    {
        try {
            parent::acl();
            $request = Yii::$app->request;
            $id = $request->post('id');
            if (in_array(1, $id)) {
                throw new \Exception('ROOT栏目不能被操作');
            }
            $type = $request->get('type');
            if (is_array($id)) {
                AdminMenu::updateAll(['state' => $type == 1 ? 1 : 2], ['in', 'id', $id]);
            } else {
                throw new \Exception('请选择ID');
            }
            AdminMenu::cached();
            parent::_renderJson(
                [
                    'code' => 200,
                    'message' => '操作完成',
                    'data' => [],
                ]
            );
        } catch (\Exception $e) {
            parent::_renderJson(
                [
                    'code' => -200,
                    'message' => $e->getMessage(),
                    'data' => [],
                ]
            );
        }
    }

    /**
     * 删除
     * @return mixed
     */
    public function actionDelete()
    {
        parent::acl();
        $id = intval(Yii::$app->request->get('id'));
        if (false == $id) {
            throw new NotFoundHttpException('ID必须提交');
        } elseif ($id == 1) {
            throw new NotFoundHttpException('根目录禁止操作');
        }
        $this->findModel($id)->deleteNode();
        AdminMenu::cached();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     * @return mixed
     */
    protected function findModel($id)
    {
        if (($model = AdminMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
