<?php
/**
 * 需求
 * 合作伙伴
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers\demand;

use bagesoft\communication\Dccomm;
use bagesoft\constant\MessageQueueConst;
use bagesoft\constant\ProjectConst;
use bagesoft\constant\System;
use bagesoft\functions\DemandFunc;
use bagesoft\models\DemandWorks;
use bagesoft\models\DemandTask;
use Exception;
use Yii;

class PartnerController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 作品上传
     *
     * @return void
     */
    public function actionWorksUpload()
    {
        parent::acl();
        $request = Yii::$app->request;
        $uid = (int) $this->session['uid'];
        $id = $request->post('id');
        try {
            $model = DemandFunc::getItemByUserMap($id, $uid);
            if (false == $model) {
                throw new Exception('创作记录不存在');
            } elseif ($model->demandTask->state == ProjectConst::DEMAND_STATUS_SUCCESS) {
                throw new Exception('该需求已经完成，无法上传作品');
            }
            $getWaitWorks = DemandFunc::getWaitWorks($model->demand->id,(int) $this->session['uid']);
            if ($getWaitWorks > 0) {
                throw new Exception('还有创作记录未审核,请等待审核结果');
            }
            //提交次数
            $produceNum = (int) DemandFunc::produceNum($model->demandTask->produce_num);
            $demandWorks = new DemandWorks();
            if (Yii::$app->request->isAjax && $demandWorks->load(Yii::$app->request->post())) {
                $demandWorks->demand_id = $model->id;
                $demandWorks->uid = $model->demand->uid;
                $demandWorks->project_id = $model->demand->project_id;
                $demandWorks->partner_uid = $model->demand->partner_uid;
                $demandWorks->partner_name = $model->demand->partner_name;
                $demandWorks->worker_uid = $uid;
                $demandWorks->worker_name = $this->session['username'];
                $demandWorks->state = ProjectConst::DEMAND_WORKS_AUDIT_WAIT;
                $demandWorks->produce_num = $produceNum;
                if ($demandWorks->validate() && $demandWorks->save()) {
                    $model->demandTask->state = ProjectConst::DEMAND_STATUS_WAIT_AUDIT;
                    $model->demandTask->produce_num = $produceNum;
                    if ($model->demandTask->worker_first_at == 0) {
                        $model->demandTask->worker_first_at = time();
                    }
                    $model->demandTask->save();
                    //发送消息
                    (new Dccomm([
                        'taskName' => '作品上传',
                        'act' => MessageQueueConst::DEMAND_WORKS_UPLOAD,
                    ]))->run([
                                'uid' => $demandWorks->uid,
                                'projectName' => $model->demand->project_name,
                            ]);
                    parent::renderSuccessJson([], '作品上传成功，请等待审核');
                } else {
                    throw new Exception('请完善表单内容');
                }
            } else {
                throw new Exception('不支持的请求方式');
            }
        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

    /**
     * 创作接受状态
     *
     * @return void
     */
    public function actionWorksAccept()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $status = $request->get('status');
        try {
            if (empty($id) || empty($status)) {
                throw new Exception('参数错误');
            }
            $model = DemandFunc::getItemByUserMap($id, (int) $this->session['uid']);
            if (false == $model) {
                throw new Exception('记录不存在');
            }
            $model->demandTask->worker_accept = $status;
            if ($status == ProjectConst::WORKS_ACCEPT_APPROVE) {
                $action = MessageQueueConst::DEMAND_ACCEPT;
                $taskName = '创作接受';
                $model->demandTask->state = ProjectConst::DEMAND_STATUS_WAIT_WORKS;
            } else {
                $action = MessageQueueConst::DEMAND_REJECT;
                $model->demandTask->state = ProjectConst::DEMAND_STATUS_WAIT_PARTNER;
                $taskName = '创作拒绝';
            }
            $model->demandTask->save();
            //发送消息
            (new Dccomm([
                'taskName' => $taskName,
                'act' => $action,
            ]))->run([
                        'uid' => $model->demand->uid,
                        'partnerUid' => $model->demand->partner_uid,
                        'adminUid' => $model->demand->operator_uid,
                        'workerName' => $model->demand->worker_name,
                        'projectName' => $model->demand->project_name,
                    ]);

            parent::renderSuccessJson([], '提交完成');

        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

    /**
     * 删除
     *
     * @return void
     */
    public function actionDelete($id)
    {
        try {
            $model = DemandFunc::getItemByUserMap($id, (int) $this->session['uid']);
            //已经接受只能伙伴通过后才能删除
            if ($model->demand->deleted == System::DELETE_LEVEL_2) {
                $model->demand->deleted = System::DELETE_LEVEL_3;
                $model->demand->save();
            }
            parent::renderSuccessJson([], '请求成功');
        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

}
