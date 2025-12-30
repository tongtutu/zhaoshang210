<?php

namespace bagesoft\models;

use bagesoft\models\User;
use bagesoft\functions\TagsFunc;
use bagesoft\models\Demand;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\models\Maintain;
use bagesoft\models\DemandWorks;
use bagesoft\models\DemandUserMap;
use bagesoft\models\InvestUserMap;
use bagesoft\models\MaintainUserMap;
/**
 * This is the model class for table "{{%invest}}".
 *
 * @property int $id 主键
 * @property int $uid 用户UID
 * @property string $username 用户名
 * @property int $partner_uid 伙伴UID(未启用)
 * @property string $partner_name 伙伴名称(未启用)
 * @property int $project_id 所属项目
 * @property string $project_name 项目名称
 * @property int $project_assess 考核项目
 * @property int $manager_uid 项目经理
 * @property string $manager_name 项目经理姓名
 * @property int $bt_manager_uid 招投标经理UID
 * @property string $bt_manager_name 招投标经理姓名
 * @property string $company_name 公司名称
 * @property string $usci_code 统一信用代码
 * @property string|null $tags 所属分类
 * @property int $channel_id 渠道
 * @property string $channel_name 渠道名称
 * @property string $contact_name 联系人
 * @property string $contact_phone 联系方式
 * @property string $province 省份
 * @property string|null $city 城市
 * @property string $area 区域
 * @property string $address 联系地址
 * @property string|null $content 项目介绍
 * @property int $steps 项目阶段
 * @property int $views 查看次数
 * @property int $is_demand 需求提交
 * @property string $attach_file 附件上传
 * @property int $deleted 删除状态
 * @property int $maintain_at 最后跟进
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class InvestDraft extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invest_draft}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // 基础字段类型校验（仅保留类型，移除强制必填）
            [['uid', 'project_assess', 'manager_uid', 'vice_manager_uid', 'channel_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['username', 'manager_name','vice_manager_name', 'usci_code', 'province', 'city', 'area'], 'string', 'max' => 50],
            [['project_name', 'company_name'], 'string', 'max' => 100],
            [['channel_name', 'attach_file'], 'string', 'max' => 255],
            [['contact_name'], 'string', 'max' => 20],
            [['contact_phone'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 200],
            [['tags'], 'string'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'uid' => '用户UID',
            'username' => '用户名',
            'project_id' => '所属项目',
            'project_name' => '项目名称',
            'project_assess' => '考核项目',
            'manager_uid' => '项目经理',
            'manager_name' => '项目经理姓名',
            'vice_manager_uid' => '招商管理岗',
            'vice_manager_name' => '招商管理岗姓名',
            'bt_manager_uid' => '招投标经理UID',
            'bt_manager_name' => '招投标经理姓名',
            'company_name' => '公司名称',
            'usci_code' => '统一信用代码',
            'tags' => '所属分类',
            'channel_id' => '渠道',
            'channel_name' => '渠道名称',
            'contact_name' => '联系人',
            'contact_phone' => '联系方式',
            'province' => '省份',
            'city' => '城市',
            'area' => '区域',
            'address' => '联系地址',
            'content' => '项目介绍',
            //'steps' => '项目阶段',
            //'views' => '查看次数',
            //'is_demand' => '需求提交',
            'attach_file' => '附件上传',
            //'deleted' => '删除状态',
            //'maintain_at' => '最后跟进',
            //'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
    /**
     * 存前操作
     *
     * @param [type] $insert
     * @param [type] $changedAttributes
     */

    /**
     * 存前操作
     *
     * @param [type] $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $user = User::findOne($this->manager_uid);
        if ($user) {
            $this->manager_name = $user->username;
        }
        $vice_user = User::findOne($this->vice_manager_uid);
        if ($vice_user) {
            $this->vice_manager_name = $vice_user->username;
        }
        $this->attach_file = str_replace(' ', '', $this->attach_file);
        if (in_array($this->scenario, ['draft'])) {
            $this->tags = TagsFunc::format($this->tags);
        }
        return true;
    }

}
