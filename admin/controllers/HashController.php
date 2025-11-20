<?php
/**
 * 校验码
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\models\Hash;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class HashController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = Hash::find();
        $request = Yii::$app->request;
        $target = trim($request->get('target'));
        $hash = trim($request->get('hash'));
        $state = intval($request->get('state'));
        if ($target) {
            $model->andWhere('target=:target', ['target' => $target]);
        }
        if ($hash) {
            $model->andWhere('hash=:hash', ['hash' => $hash]);
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
     * 详情
     */
    public function actionItem()
    {
        parent::acl();
        $request = Yii::$app->request;
        $size = trim($request->get('size'));
        $id = intval($request->get('id'));
        $model = $this->findModel($id);

        if ($size == 'mini') {
            $this->layout = 'mini';
        }
        return $this->render('item',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 加载模型
     */
    public function findModel($id)
    {
        if (($model = Hash::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
