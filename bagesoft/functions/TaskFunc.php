<?php
/**
 * 创作
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

use bagesoft\models\Demand;
use bagesoft\constant\System;
use bagesoft\models\DemandWorks;
use bagesoft\models\DemandUserMap;
use bagesoft\models\DemandTask;
use bagesoft\constant\ProjectConst;

class DemandFunc
{
    /**
     * 单条记录
     *
     * @param integer $id
     * @param integer $uid
     * @return object
     */
    public static function getWorksItemByIdAndUid($id, $uid)
    {
        return DemandWorks::find()->where('id=:id AND uid=:uid', ['id' => $id, 'uid' => $uid])->limit(1)->one();
    }

    /**
     * 单条记录
     *
     * @param integer $id
     * @param integer $uid
     * @return object
     */
    public static function getWorksItemByIdAndUidPartnerUid($id, $uid)
    {
        return DemandWorks::find()->where('id=:id AND (uid=:uid OR partner_uid=:parterUid)', ['id' => $id, 'uid' => $uid, 'parterUid' => $uid])->limit(1)->one();
    }


    /**
     * 单条记录
     *
     * @param integer $id
     * @param integer $uid
     * @return object
     */
    public static function getItemByIdAndUid($id, $uid)
    {
        return Demand::find()->where('id=:id AND uid=:uid', ['id' => $id, 'uid' => $uid])->limit(1)->one();
    }

    /**
     * 通过map查询demand
     *
     * @param integer $id
     * @param integer $uid
     * @return mixed
     */
    public static function getItemByUserMap($id, $uid)
    {
        return DemandUserMap::find()->alias('userMap')->joinWith('demand AS demand')->select('demand.*,userMap.uid,userMap.demand_id')->where('demand.id=:id AND userMap.uid=:uid', ['id' => $id, 'uid' => $uid])->limit(1)->one();
    }

    /**
     * 记录
     *
     * @param integer $id
     * @param integer $uid
     * @return object
     */
    public static function getItemById($id)
    {
        return Demand::findOne($id);
    }

    /**
     * 统计待审核数量
     *
     * @param integer $demandId
     * @return integer
     */
    public static function getWaitWorks($demandId)
    {
        return DemandWorks::find()->where('demand_id=:demand_id AND state=:state', ['demand_id' => $demandId, 'state' => ProjectConst::DEMAND_WORKS_AUDIT_WAIT])->count();
    }

    /**
     * 创作次数
     *
     * @param integer $currentNum
     * @return integer
     */
    public static function produceNum($currentNum)
    {
        if ($currentNum < ProjectConst::DEMAND_WORKS_UPLOAD_3) {
            $num = $currentNum + 1;
            return $num;
        } else {
            return ProjectConst::DEMAND_WORKS_UPLOAD_N;
        }
    }

    /**
     * 
     * 添加关联记录
     *
     * @param mixed $demandId
     * @param mixed $uid
     * @param mixed $roleType
     * @return void
     */
    public static function addMap($demandId, $uid, $roleType = System::WORKER)
    {
        $map = new DemandUserMap();
        $map->demand_id = $demandId;
        $map->uid = $uid;
        $map->role_type = $roleType;
        $map->save();
    }

    /**
     * 
     * 更新关联记录
     *
     * @param mixed $demandId
     * @param mixed $uid
     * @param mixed $roleType
     * @return void
     */
    public static function updateMap($demandId, $uid, $roleType = System::WORKER)
    {
        $map = DemandUserMap::find()->where('demand_id=:demand_id AND role_type=:role_type', ['demand_id' => $demandId, 'role_type' => $roleType])->one();
        if ($map) {
            $map->uid = $uid;
            $map->save();
        }
    }
}
