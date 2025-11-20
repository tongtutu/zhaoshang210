<?php
/**
 * 单页
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\library\Storage;
use bagesoft\models\Page;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class PageController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        parent::acl();
        $model = Page::find();
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
        parent::acl();
        $model = new Page();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $upload = Storage::upload('image', ['thumb' => true, 'thumbSize' => '200x200']);
            if (is_array($upload)) {
                $model->image = $upload['file'];
                $model->image_thumb = $upload['thumb'];
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $oldAttrs = $model->oldAttributes;
            $upload = Storage::upload('image', ['thumb' => true, 'thumbSize' => '200x200']);
            if (is_array($upload)) {
                $model->image = $upload['file'];
                $model->image_thumb = $upload['thumb'];
                $delete = true;
            }
            if ($model->save()) {
                if ($delete) {
                    @unlink('./' . $oldAttrs['image']);
                    @unlink('./' . $oldAttrs['image_thumb']);
                }
                return $this->redirect(['index']);
            }
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
        $id = intval(Yii::$app->request->get('id'));
        $model = $this->findModel($id);
        if ($model->image) {
            @unlink('./' . $model->image);
            @unlink('./' . $model->image_thumb);
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
