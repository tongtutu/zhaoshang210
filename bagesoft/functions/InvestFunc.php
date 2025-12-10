<?php
/**
 * 招商
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\functions;

use bagesoft\constant\System;
use bagesoft\constant\UserConst;
use bagesoft\models\Invest;
use bagesoft\models\InvestExt;
use bagesoft\models\InvestUserMap;

class InvestFunc
{
    /**
     * 根据ID和UID获取信息
     *
     * @param integer $id
     * @param integer $uid
     * @return object
     */
    public static function getItemByIdAndUid($id, $uid)
    {
        return Invest::find()->where('id=:id AND uid=:uid', ['id' => $id, 'uid' => $uid])->limit(1)->one();
    }
    /**
     * 根据UID或管理者ID获取信息
     *
     * @param integer $id
     * @param integer $uid
     * @return object
     */
    public static function getItemByUidOrMgrId($id, $uid)
    {
        return Invest::find()->where('id=:id AND (uid=:uid OR manager_uid=:managerUid OR vice_manager_uid=:vicemanagerUid )', ['id' => $id, 'uid' => $uid, 'managerUid' => $uid , 'vicemanagerUid' => $uid])->limit(1)->one();
    }

    /**
     * 根据ID获取信息
     *
     * @param integer $id
     * @return object
     */
    public static function getItemById($id)
    {
        return Invest::find()->where('id=:id', ['id' => $id])->limit(1)->one();
    }

    /**
     * 获取MAP信息
     * 
     * @param integer $projectId
     * @param integer $uid
     * @param integer $roleType
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getMap($projectId, $roleType)
    {
        return InvestUserMap::find()->where('project_id=:project_id AND role_type=:role_type', ['project_id' => $projectId, 'role_type' => $roleType])->one();
    }

    /**
     * 设置招投标需求
     * @param $id
     * @param string $field 字段名
     * @param string $type set:设置招投标需求,unset:取消招投标需求
     */
    public static function setExtVal($id, $field = 'bt_request', $type = 'set')
    {
        $getExt = InvestExt::find()->where('project_id=:id', ['id' => $id])->limit(1)->one();
        if (false == $getExt) {
            $getExt = new InvestExt();
            $getExt->project_id = $id;
        }
        switch ($type) {
            case 'set':
                $value = System::YES;
                break;
            default:
                $value = System::NO;
                break;
        }
        $getExt->$field = $value;
        $getExt->save();
    }

    /**
     * 删除关联关系
     * @param integer $projectId
     * @param integer $uid
     * @param integer $roleType
     * @return void
     */
    public static function mapDestory($projectId, $roleType)
    {
        InvestUserMap::deleteAll('project_id=:project_id AND role_type=:role_type', ['project_id' => $projectId, 'role_type' => $roleType]);
    }

    /**
     * 过户
     * @param object $project
     * @param object $newUser
     * @return void
     */
    public static function transfer($project, $newUser)
    {
        if ($project->manager_uid == $newUser->id) {
            //如果新用户是当前项目经理，则删除原项目经理的管理关系
            self::mapDestory($project->id, System::MANAGER);
            $project->manager_uid = 0;
            $project->manager_name = '';
            $project->uid = $newUser->id;
            $project->username = $newUser->username;
        } else {
            //替换项目所有者、替换MAP所有者
            $owner = self::getMap($project->id, System::OWNER);
            if ($owner) {
                $owner->uid = $newUser->id;
                $owner->save();
                $project->uid = $newUser->id;
                $project->username = $newUser->username;
            }
        }
        $project->save();
    }
}
