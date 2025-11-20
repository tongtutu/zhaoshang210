<?php
/**
 * 推送日志
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\models\SendLog;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class SendLogController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = SendLog::find();
        $request = Yii::$app->request;
        $target = trim($request->get('target'));
        $module = trim($request->get('module'));
        $date = trim($request->get('date'));
        if ($target) {
            $model->andWhere('target=:target', ['target' => $target]);
        }
        if ($module) {
            $model->andWhere('module=:module', ['module' => $module]);
        }
        if ($date) {
            $model->andWhere('date_at=:date_at', ['date_at' => str_replace('-', '', $date)]);
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
        if (($model = SendLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
