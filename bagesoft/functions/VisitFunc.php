<?php
/**
 * 访问记录
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

use bagesoft\models\Visit;
use bagesoft\constant\System;

class VisitFunc
{
    /**
     * 获取用户
     *
     * @param integer $uid
     * @return void
     */
    public static function log($session, $project, $source)
    {
        // if ($session['role'] == System::USER_ROLE_ADMIN && $session['uid'] == 1) {
        //     return;!!!debug!!!
        // }
        //只需通知当事人
        $model = new Visit();
        $model->uid = intval($project->uid);
        $model->project_source = intval($source);
        $model->visit_source = intval($session['role']);
        $model->visit_uid = intval($session['uid']);
        $model->visit_user = trim($session['username']);
        $model->project_id = intval($project->id);
        $model->project_name = trim($project->project_name);
        $model->save();
    }

}
