<?php
/**
 * 市场信息
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers\customer;

use bagesoft\constant\System;
use bagesoft\functions\CustomerFunc;
use bagesoft\helpers\Utils;
use Yii;

class PartnerController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 合作伙伴审核
     *
     * @return void
     */
    public function actionAccept()
    {
        parent::acl();
        $request = Yii::$app->request;
        $id = $request->get('id');

        try {
            $project = CustomerFunc::getItemByIdAndParterUid($id, $this->session['uid']);

            if (false == $project) {
                throw new \Exception('项目不存在');
            } elseif ($project->partner_accept == System::APPROVE) {
                throw new \Exception('项目已经审核');
            }
            $project->partner_accept = System::APPROVE;
            $project->save();
            parent::renderSuccessJson([], '审核完成');
        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

}
