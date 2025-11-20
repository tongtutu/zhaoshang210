<?php
/**
 * 内容
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use bagesoft\library\Storage;
use bagesoft\models\Post;
use bagesoft\models\PostAlbum;
use bagesoft\models\PostClassMap;
use bagesoft\models\PostReply;
use bagesoft\models\PostTagsMap;
use bagesoft\models\TopicDataMap;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class PostController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = Post::find();
        $request = Yii::$app->request;
        $title = trim($request->get('title'));
        $state = intval($request->get('state'));
        if ($title) {
            $model->andWhere(['like', 'title', $title]);
        }
        if ($state) {
            $model->andWhere('state=:state', ['state' => $state]);
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
        parent::acl();
        $model = new Post();
        if ($model->load(Yii::$app->request->post())) {
            $model->class = Post::classFmt($model->class);
            $model->tags = Post::tagsFmt($model->tags);
            $model->topic = $model->topic ? implode(',', $model->topic) : '';
            if ($model->validate()) {
                $upload = Storage::upload('image', ['thumb' => true, 'thumbSize' => '200x200']);
                if (is_array($upload)) {
                    $model->image = $upload['file'];
                    $model->image_thumb = $upload['thumb'];
                    $model->image_state = 1;
                }
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        $model->loadDefaultValues();
        $model->created_at = date('Y-m-d H:i:s');
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
        if ($model->load(Yii::$app->request->post())) {
            $oldAttrs = $model->oldAttributes;
            $model->class = Post::classFmt($model->class);
            $model->tags = Post::tagsFmt($model->tags);
            $model->topic = $model->topic ? implode(',', $model->topic) : '';
            if ($model->validate()) {
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
            }
        }
        $model->tags = $model->tags ? implode(',',
            Post::tagsUnfmt(
                [
                    'tags' => $model->tags,
                    'typeof' => 'v',
                ]
            )
        ) : '';

        $model->topic = $model->topic ? explode(',', $model->topic) : [];
        $model->class = $model->class ? Post::classUnfmt($model->class, 'k') : '';
        $model->created_at = date('Y-m-d H:i:s', $model->created_at);
        return $this->render('update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 状态变更
     * @return
     */
    public function actionState()
    {
        try {
            parent::acl();
            $request = Yii::$app->request;
            $id = $request->post('id');
            $type = $request->get('type');
            if (is_array($id)) {
                Post::updateAll(['state' => $type == 1 ? 1 : 2], ['in', 'id', $id]);
            } else {
                throw new \Exception('请选择ID');
            }
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
     * 内容删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDelete()
    {
        try {
            parent::acl();
            $request = Yii::$app->request;
            if ($request->isPost) {
                $id = $request->post('id');
                $method = 'ajax';
            } elseif ($request->isGet) {
                $id = $request->get('id');
                $method = 'get';
            }

            if (empty($id)) {
                throw new \Exception('ID号必须提交');
            }
            $this->_delete($id);
            parent::_renderMessage($method,
                [
                    'code' => 200,
                    'message' => '删除成功',
                    'next' => '',
                    'data' => [],
                ]
            );
        } catch (\Exception $e) {
            parent::_renderMessage($method,
                [
                    'code' => -200,
                    'message' => $e->getMessage(),
                    'next' => '',
                    'data' => [],
                ]
            );
        }
    }

    /**
     * 公共删除操作
     * 提升效率，单条多条删除分开
     * @param  string|array $id ID
     * @return
     */

    private function _delete($id)
    {
        if (is_array($id)) {
            PostAlbum::deleteAll(['in', 'post_id', $id]);
            PostClassMap::deleteAll(['in', 'post_id', $id]);
            PostReply::deleteAll(['in', 'post_id', $id]);
            PostTagsMap::deleteAll(['in', 'post_id', $id]);
            TopicDataMap::deleteAll(['in', 'data_id', $id]);
            foreach ($id as $val) {
                $model = Post::findOne($val);
                if ($model) {
                    @unlink('./' . $model->image);
                    @unlink('./' . $model->image_thumb);
                    $model->delete();
                }
            }
        } else {
            $model = Post::findOne($id);
            Post::deleteAll('id=:id', ['id' => $id]);
            PostAlbum::deleteAll('post_id=:post_id', ['post_id' => $id]);
            PostClassMap::deleteAll('post_id=:post_id', ['post_id' => $id]);
            PostReply::deleteAll('post_id=:post_id', ['post_id' => $id]);
            PostTagsMap::deleteAll('post_id=:post_id', ['post_id' => $id]);
            TopicDataMap::deleteAll('data_id=:data_id', ['data_id' => $id]);
            if ($model) {
                @unlink('./' . $model->image);
                @unlink('./' . $model->image_thumb);
                $model->delete();
            }
        }
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
