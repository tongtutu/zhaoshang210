<?php
/**
 * 项目
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\functions;

use bagesoft\constant\AdminConst;
use bagesoft\constant\System;
use bagesoft\helpers\Utils;
use bagesoft\models\Tags;

class ProjectFunc
{
    /**
     * 列出 TAGS 清单
     *
     * @return array
     */
    public static function listTags()
    {
        $tagsList = [];
        $cats = CatsFunc::getListByMod(System::CATS_KEYID_TAGS);
        foreach ((array) $cats as $row) {
            $getCat = self::listTagByCat($row['id']);
            if (false == $getCat) {
                $getCat = [];
            }
            $tagsList[$row['id']] = [
                'id' => $row['id'],
                'name' => $row['cat_name'],
                'list' => $getCat,
            ];
        }
        return $tagsList;
    }

    /**
     * 根据类型查找 TAGS
     *
     * @param integer $catid
     */
    private static function listTagByCat($catid)
    {
        return Tags::find()->where('catid=:catid', ['catid' => $catid])->orderBy('id DESC')->asArray()->all();
    }

    /**
     * 隐藏姓名
     *
     * @param integer $uid
     * @param integer $sid
     * @param mixed $data
     */
    public static function hideName($uid, $sid, $data)
    {
        if ($uid == $sid) {
            return $data;
        } else {
            return Utils::hideName($data);
        }
    }


    /**
     * 隐藏电话
     *
     * @param integer $uid
     * @param integer $sid
     * @param mixed $data
     * @return void
     */
    public static function hidePhone($uid, $sid, $data)
    {
        if ($uid == $sid) {
            return $data;
        } else {
            return Utils::hidePhone($data);
        }
    }

    /**
     * 隐藏所有
     *
     * @param integer $uid
     * @param integer $sid
     * @param mixed $data
     * @return void
     */
    public static function hideAll($uid, $sid, $data)
    {
        if ($uid == $sid) {
            return $data;
        } else {
            return '***';
        }
    }


    /**
     * 隐藏地址
     *
     * @param integer $uid
     * @param integer $sid
     * @param mixed $data
     * @return void
     */
    public static function hideAdd($uid, $sid, $data)
    {
        if ($uid == $sid) {
            return $data;
        } else {
            return '*';
        }
    }


    /**
     * ID获取项目
     *
     * @param integer $id
     * @param integer $source
     * @return object
     */
    public static function getItemById($id, $source)
    {
        if ($source == System::SOURCE_CUSTOMER) {
            $project = CustomerFunc::getItemById($id);
        } else {
            $project = InvestFunc::getItemById($id);
        }
        return $project;
    }


    /**
     * 隐藏姓名_后台
     *
     * @param object $admin
     * @param mixed $data
     * @return
     */
    public static function adminHideName($admin, $data)
    {
        if ($admin instanceof \bagesoft\models\Admin && in_array($admin->gid, AdminConst::SUPER_GIDS)) {
            return $data;
        } else {
            return Utils::hideName($data);
        }
    }


    /**
     * 隐藏地址_后台
     *
     * @param object $admin
     * @param mixed $data
     * @return void
     */
    public static function adminHideAdd($admin, $data)
    {
        if (in_array($admin->id, AdminConst::SUPER_UIDS) || in_array($admin->gid, AdminConst::SUPER_GIDS)) {
            return $data;
        } else {
            return '*';
        }
    }


    /**
     * 隐藏电话_后台
     *
     * @param object $admin
     * @param mixed $data
     * @return mixed
     */
    public static function adminHidePhone($admin, $data)
    {
        if (in_array($admin->id, AdminConst::SUPER_UIDS) || in_array($admin->gid, AdminConst::SUPER_GIDS)) {
            return $data;
        } else {
            return Utils::hidePhone($data);
        }
    }

    /**
     * 隐藏所有_后台
     * @param object $admin
     * @param mixed $data
     */
    public static function adminHideAll($admin, $data)
    {
        if (in_array($admin->id, AdminConst::SUPER_UIDS) || in_array($admin->gid, AdminConst::SUPER_GIDS)) {
            return $data;
        } else {
            return '*';
        }
    }

    /**
     * 分配招投标
     *
     * @param mixed $project
     * @throws \Exception
     * @return void
     */
    public static function assignBtManager($project, $oldManagerId)
    {
        $user = UserFunc::getUserById($project->bt_manager_uid);
        if (false == $user) {
            throw new \Exception('招投标经理不存在');
        }
        $project->bt_manager_uid = $user->id;
        $project->bt_manager_name = $user->username;
        $project->save();

        CustomerFunc::setExtVal($project->id, 'bt_request_respond', 'set');

        if ($oldManagerId == 0) {
            CustomerFunc::addUserMap($project->id, $project->bt_manager_uid);
        } elseif ($project->bt_manager_uid != $oldManagerId) {
            CustomerFunc::updateUserMap($project->id, $project->bt_manager_uid);
        }
    }

    /**
     *
     * 拓展类型
     * @param mixed $id
     * @return string
     */
    public static function getExpandTypeName($id)
    {
        $tag =  Tags::findOne($id);
        return $tag->tag_name;
    }

}
