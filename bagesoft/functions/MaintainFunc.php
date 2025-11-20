<?php
/**
 * 跟进维护
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

use bagesoft\constant\System;
use bagesoft\models\Maintain;
use bagesoft\models\MaintainUserMap;
use bagesoft\models\User;
use yii\data\Pagination;

class MaintainFunc
{
    /**
     * 根据ID和UID取记录
     *
     * @param integer $uid
     * @return object
     */
    public static function getItemByIdAndUid($id, $uid)
    {
        return Maintain::find()->where('id=:id AND uid=:uid', ['id' => $id, 'uid' => $uid])->limit(1)->one();
    }

    /**
     * 根据ID取记录
     *
     * @param integer $id
     * @return object
     */
    public static function getItemById($id)
    {
        return Maintain::findOne($id);
    }

    /**
     * 获取跟进列表
     *
     * @param integer $id
     * @param integer $source
     * @return array
     */
    public static function getMaintianListById($id, $source = System::SOURCE_CUSTOMER)
    {
        $model = Maintain::find()->alias('maintain');
        $model->leftJoin(User::tableName() . 'u', 'maintain.uid=u.id');
        $model->where('project_id=:projectId AND source=:source', ['projectId' => $id, 'source' => $source]);
        $model->select(['maintain.*', 'u.username']);
        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $maintains = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('maintain.id DESC')->asArray()->all();
        return [
            'pagination' => $pagination,
            'datalist' => $maintains,
        ];
    }

    /**
     * 通过map查询customer
     *
     * @param integer $id
     * @param integer $uid
     * @return mixed
     */
    public static function getItemByUserMap($id, $uid)
    {
        return MaintainUserMap::find()->alias('userMap')->joinWith('maintain AS maintain')->select('maintain.*,userMap.uid,userMap.maintain_id')->where('maintain.id=:id AND userMap.uid=:uid', ['id' => $id, 'uid' => $uid])->limit(1)->one();
    }

    /**
     * 根据会话用户ID和项目信息返回合作伙伴信息
     *
     * @param mixed $sessionUid 当前会话用户的ID
     * @param object $project 项目信息数组，包含 'uid', 'partner_uid', 'partner_name', 'username' 等键
     * @return array 返回包含合作伙伴用户ID和名称的数组
     */
    public static function selectUser($sessionUid, $project)
    {
        if ($sessionUid == $project['uid']) {
            return [
                'partner_uid' => (int) $project['partner_uid'],
                'partner_name' => $project['partner_name'] ?? '',
            ];
        } else {
            return [
                'partner_uid' => (int) $project['uid'],
                'partner_name' => $project['username'] ?? '',
            ];
        }
    }

}
