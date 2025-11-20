<?php

namespace bagesoft\models;

use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\constant\ProjectConst;
/**
 * This is the model class for table "{{%demand}}".
 *
 * @property int $id ID
 * @property int $source 来源
 * @property int $uid 用户ID
 * @property string $username 提交人
 * @property int $partner_uid 合作者UID
 * @property string $partner_name 合作者名称
 * @property int $worker_uid 创作者UID
 * @property string $worker_name 创作者名称
 * @property int $worker_accept 创作接受状态
 * @property int $project_id 项目ID
 * @property string $project_name 项目名称
 * @property string $project_location 项目所在地
 * @property string $hope_time 期望首次提交时间
 * @property string|null $content 项目介绍
 * @property string|null $attach_file 附件上传
 * @property int $state 状态
 * @property int $worker_state 作品状态
 * @property int $worker_first_at 首次提交
 * @property int $worker_succ_at 完成时间
 * @property int $operator_uid 审批人UID
 * @property string $operator_name 审批人
 * @property int $produce_num 创作次数
 * @property int $deleted 删除状态
 * @property int $is_remind 提醒状态
 * @property int $remind_at 期望首次提交时间戳
 * @property int $updated_at 最后更新
 * @property int $created_at 入库时间
 */
class Demand extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%demand}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source', 'uid', 'partner_uid', 'worker_uid', 'worker_accept', 'project_id', 'state', 'worker_state', 'worker_first_at', 'worker_succ_at', 'operator_uid', 'produce_num', 'deleted', 'is_remind', 'remind_at', 'updated_at', 'created_at'], 'integer'],
            [['project_location', 'hope_time'], 'required'],
            [['content', 'attach_file'], 'string'],
            [['username', 'partner_name', 'worker_name', 'operator_name'], 'string', 'max' => 50],
            [['project_name'], 'string', 'max' => 150],
            [['project_location'], 'string', 'max' => 100],
            [['hope_time'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source' => '来源',
            'uid' => '用户ID',
            'username' => '提交人',
            'partner_uid' => '合作者UID',
            'partner_name' => '合作者名称',
            'worker_uid' => '创作者UID',
            'worker_name' => '创作者名称',
            'worker_accept' => '创作接受状态',
            'project_id' => '项目ID',
            'project_name' => '项目名称',
            'project_location' => '项目所在地',
            'hope_time' => '期望首次提交时间',
            'content' => '项目介绍',
            'attach_file' => '附件上传',
            'state' => '状态',
            'worker_state' => '作品状态',
            'worker_first_at' => '首次提交',
            'worker_succ_at' => '完成时间',
            'operator_uid' => '审批人UID',
            'operator_name' => '审批人',
            'produce_num' => '创作次数',
            'deleted' => '删除状态',
            'is_remind' => '提醒状态',
            'remind_at' => '期望首次提交时间戳',
            'updated_at' => '最后更新',
            'created_at' => '入库时间',
        ];
    }

    /**
     * 存前操作
     *
     * @param [type] $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->remind_at = intval(strtotime($this->hope_time));
            $this->attach_file = str_replace(' ', '', $this->attach_file);
            $timestamp = time();
            if ($this->remind_at >= $timestamp) {
                $this->is_remind = ProjectConst::MAINTAIN_REMIND_YES;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 存后操作
     *
     * @param [type] $insert
     * @param [type] $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            //发布者
            $userMap = new DemandUserMap();
            $userMap->uid = intval($this->uid);
            $userMap->demand_id = $this->id;
            $userMap->role_type = System::OWNER;
            $userMap->save();
            if ($this->source == System::SOURCE_CUSTOMER) {
                //伙伴
                $target = new DemandUserMap();
                $target->uid = intval($this->partner_uid);
                $target->demand_id = $this->id;
                $target->role_type = System::PARTNER;
                $target->save();
            }
            //关联项目
            UploadFunc::relateProject($this->attach_file, $this->id);
        }
    }

    /**
     * 删前操作
     *
     * @param [type] $insert
     * @param [type] $changedAttributes
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        DemandUserMap::deleteAll('demand_id=:demandId', ['demandId' => $this->id]);
        DemandWorks::deleteAll('demand_id=:demandId', ['demandId' => $this->id]);
        return true;
    }

}
