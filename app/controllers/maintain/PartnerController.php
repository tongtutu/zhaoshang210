<?php
/**
 * 需求
 * 合作伙伴
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers\maintain;

use Yii;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\functions\ProjectFunc;
use bagesoft\functions\MaintainFunc;
use bagesoft\communication\Dccomm;
use bagesoft\constant\ProjectConst;
use bagesoft\constant\MessageQueueConst;

class PartnerController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 审核
     */
    public function actionAudit()
    {
        try {
            parent::acl();
            $request = Yii::$app->request;
            $maintianId = intval($request->post('maintainId'));
            $model = MaintainFunc::getItemById($maintianId);
            if (false == $model) {
                throw new \Exception('记录不存在');
            } elseif ($model->partner_uid != $this->session['uid']) {
                //非合作伙伴不能审核
                throw new \Exception('非合作伙伴不能审核');
            }

            $project = ProjectFunc::getItemById($model->project_id, $model->source);
            if (false == $project) {
                throw new \Exception('项目不存在');
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //更新项目信息
                $project->steps = $model->steps;
                $project->maintain_at = time();
                $project->save();
                $model->check_at = time();
                if (!$model->save()) {
                    throw new \Exception('保存失败');
                }
                //招投标阶段+审核通过——通知管理
                if ($model->steps == ProjectConst::STEPS_4 && $model->bt_demand == System::YES && $model->state == ProjectConst::MAINTAIN_STATUS_PASS) {
                    (new Dccomm([
                        'taskName' => '招投标阶段_通知管理员',
                        'act' => MessageQueueConst::PROJECT_STEPS_BT_TOADMIN,
                    ]))->run([
                                'projectName' => $model->project_name,
                                'sourceName' => System::SOURCE[$model->source],
                            ]);
                }
                parent::renderSuccessJson();
            } else {
                throw new \Exception('请检查输入内容是否完整');
            }

            parent::renderSuccessJson();

        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

}
