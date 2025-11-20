<?php
namespace bagesoft\models;

use bagesoft\constant\System;
use bagesoft\functions\InvestFunc;
use bagesoft\functions\UploadFunc;
use bagesoft\functions\CustomerFunc;
use bagesoft\constant\ProjectConst;
use bagesoft\models\MaintainUserMap;
/**
 * This is the model class for table "{{%maintain}}".
 *
 * @property int $id ID
 * @property int $source 类型
 * @property int $uid 用户ID
 * @property string $username 用户名
 * @property int $partner_uid 伙伴UID
 * @property string|null $partner_name 伙伴名称
 * @property int $typeid 维护类型
 * @property int $project_id 项目ID
 * @property string $project_name 项目名称
 * @property int $bt_demand 招投标需求
 * @property int $steps 项目阶段
 * @property string|null $content 情况描述
 * @property string|null $prove_file 证明资料
 * @property int $state 审核状态
 * @property int $check_at 审核时间
 * @property int $deleted 删除状态
 * @property string|null $args 参数
 * @property string $remind_time 下次跟进提醒
 * @property int $remind_at 下次跟进提醒_时间戳
 * @property int|null $is_remind 提醒状态
 * @property int $updated_at 最后更新
 * @property int $created_at 入库时间
 */
class Maintain extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%maintain}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source', 'uid', 'partner_uid', 'typeid', 'project_id', 'bt_demand', 'steps', 'state', 'check_at', 'deleted', 'remind_at', 'is_remind', 'updated_at', 'created_at'], 'integer'],
            [['content', 'args'], 'string'],
            [['username', 'partner_name'], 'string', 'max' => 100],
            [['project_name'], 'string', 'max' => 150],
            [['prove_file'], 'string', 'max' => 200],
            [['remind_time'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source' => '类型',
            'uid' => '用户ID',
            'username' => '用户名',
            'partner_uid' => '伙伴UID',
            'partner_name' => '伙伴名称',
            'typeid' => '维护类型',
            'project_id' => '项目ID',
            'project_name' => '项目名称',
            'bt_demand' => '招投标需求',
            'steps' => '项目阶段',
            'content' => '情况描述',
            'prove_file' => '证明资料',
            'state' => '审核状态',
            'check_at' => '审核时间',
            'deleted' => '删除状态',
            'args' => '参数',
            'remind_time' => '下次跟进提醒',
            'remind_at' => '下次跟进提醒_时间戳',
            'is_remind' => '提醒状态',
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
        $this->bt_demand = intval($this->bt_demand);
        if (parent::beforeSave($insert)) {
            $this->remind_at = abs(intval(strtotime($this->remind_time)));
            $timestamp = time();
            if ($this->remind_at >= $timestamp) {
                $this->is_remind = ProjectConst::MAINTAIN_REMIND_YES;
            }
            $this->prove_file = str_replace(' ', '', $this->prove_file);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 存前操作
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
            $userMap = new MaintainUserMap();
            $userMap->uid = intval($this->uid);
            $userMap->maintain_id = $this->id;
            $userMap->role_type = System::OWNER;
            $userMap->save();
            if ($this->partner_uid > 0 && $this->source == System::SOURCE_CUSTOMER) {
                $userMap1 = new MaintainUserMap();
                $userMap1->uid = intval($this->partner_uid);
                $userMap1->maintain_id = $this->id;
                $userMap->role_type = System::PARTNER;
                $userMap1->save();
            }
        }
        //关联项目附件
        UploadFunc::relateProject($this->prove_file, $this->id);

        //创建招投标需求
        if ($this->bt_demand == System::YES) {
            switch ($this->source) {
                case System::SOURCE_CUSTOMER:
                    CustomerFunc::setExtVal($this->project_id, 'bt_request', 'set');
                    break;
                case System::SOURCE_INVEST:
                    InvestFunc::setExtVal($this->project_id, 'bt_request', 'set');
                    break;
            }
        }
    }
}
