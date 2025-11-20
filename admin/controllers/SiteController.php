<?php
/**
 * 系统首页
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */
namespace admin\controllers;

use bagesoft\constant\System;
use bagesoft\models\CustomerExt;
use bagesoft\models\Demand;
use bagesoft\models\Invest;
use bagesoft\models\Customer;
use bagesoft\models\Maintain;
use bagesoft\models\DemandWorks;
use bagesoft\constant\ProjectConst;

class SiteController extends \bagesoft\common\controllers\admin\Base
{

    public function actionIndex()
    {
        $uid = $this->session['uid'];
        $customerNum = Customer::find()->count();//市场信息数
        $customerWaitBtNum = Customer::find()->alias('customer')->leftJoin(CustomerExt::tableName() . ' ext', 'ext.project_id=customer.id')->where('customer.steps=:steps AND ext.bt_request=:btRequest AND ext.bt_request_respond=:btRequestRespond', ['btRequest' => System::YES, 'btRequestRespond' => System::NO, 'steps' => ProjectConst::STEPS_4])->count();//市场招投标经理

        $customerWaitAcceptNum = Customer::find()->where('partner_uid=:uid and partner_accept=:partnerAccept', ['uid' => $uid, 'partnerAccept' => ProjectConst::PARTNER_ACCEPT_WAIT])->count();//市场信息数_待审核

        $investNum = Invest::find()->count();//招商信息数

        $demandNum = Demand::find()->count();//需求信息数

        $maintainWaitAuditNum = Maintain::find()->where('partner_uid=:partnerUid AND state=:state', ['partnerUid' => $uid, 'state' => ProjectConst::MAINTAIN_STATUS_WAIT])->count();//待审核维护信息数

        $demandWaitWorkerNum = Demand::find()->where('worker_uid=0')->count();//分配创作人

        $maintainNum = Maintain::find()->count();//跟进维护信息数

        return $this->render(
            'index',
            [
                'customerNum' => $customerNum,
                'customerWaitAcceptNum' => $customerWaitAcceptNum,
                'customerWaitBtNum' => $customerWaitBtNum,
                'investNum' => $investNum,
                'demandNum' => $demandNum,
                'maintainWaitAuditNum' => $maintainWaitAuditNum,
                'demandWorksWaitAcceptNum' => $demandWaitWorkerNum,
                'maintainNum' => $maintainNum,
            ]
        );
    }

    public function actionAjax()
    {
        return $this->renderAjax('ajax', [
        ]);

    }
}
