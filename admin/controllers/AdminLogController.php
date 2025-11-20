<?php
/**
 * 行为日志
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\models\AdminLog;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class AdminLogController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();

        $model = AdminLog::find();

        $request = Yii::$app->request;
        $method = trim($request->get('method'));
        $ip = trim($request->get('ip'));
        $username = trim($request->get('username'));
        $uid = intval($request->get('uid'));
        if ($method) {
            $model->andWhere('method=:method', ['method' => $method]);
        }
        if ($uid > 0) {
            $model->andWhere('uid=:uid', ['uid' => $uid]);
        }
        if ($username) {
            $model->andWhere('username=:username', ['username' => $username]);
        }
        if ($ip) {
            $model->andWhere('ip=:ip', ['ip' => $ip]);
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
     * 查看详情
     * @return
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
        return $this->render('item', [
            'model' => $model,
        ]);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = AdminLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }
}
