<?php

namespace bagesoft\models;

use bagesoft\functions\TagsFunc;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\models\CustomerUserMap;
/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id 主键
 * @property int $uid 用户UID
 * @property string $username 用户名
 * @property int $partner_uid 合作伙伴
 * @property string $partner_name 合作伙伴名称
 * @property int $partner_accept 合作伙伴审核
 * @property string $project_name 项目名称
 * @property int $manager_uid 项目经理UID
 * @property string $manager_name 项目经理姓名
 * @property int $bt_manager_uid 招投标经理UID
 * @property string $bt_manager_name 招投标经理姓名
 * @property string $company_name 公司名称
 * @property int $expand_type 拓展类型
 * @property int $stars 客户星级
 * @property string|null $tags 项目类型
 * @property string $realname 客户全名
 * @property int $sex 性别
 * @property string $usci_code 统一信用代码
 * @property string $job_title 职务
 * @property string $phone 联系方式1
 * @property string $phone1 联系方式2
 * @property string $province 省份
 * @property string $city 城市
 * @property string $area 区域
 * @property string $address 联系地址
 * @property string|null $content 项目介绍
 * @property int $steps 项目阶段
 * @property int $views 查看次数
 * @property int $deleted 删除状态
 * @property int $is_demand 需求提交
 * @property string $attach_file 附件上传
 * @property int $maintain_at 最后跟进
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class Customer extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'partner_uid', 'partner_accept', 'manager_uid', 'bt_manager_uid', 'expand_type', 'stars', 'sex', 'steps', 'views', 'deleted', 'is_demand', 'maintain_at', 'updated_at', 'created_at'], 'integer'],
            [['partner_uid', 'project_name', 'company_name', 'realname', 'usci_code', 'job_title', 'phone', 'province', 'city', 'address', 'attach_file'], 'required'],
            [['content'], 'string'],
            [['username', 'partner_name', 'manager_name', 'bt_manager_name', 'usci_code', 'job_title', 'province', 'city', 'area'], 'string', 'max' => 50],
            [['project_name'], 'string', 'max' => 150],
            [['company_name'], 'string', 'max' => 100],
            [['realname', 'phone', 'phone1'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 200],
            [['attach_file'], 'string', 'max' => 255],
            [
                'phone',
                'match', // Yii2原生正则匹配验证器
                'pattern' => '/^1[3-9]\d{9}$/', // 中国大陆11位手机号正则
                'skipOnEmpty' => true, // 空值时跳过（空值校验由原有required规则处理）
                'message' => '{attribute}格式错误，请输入有效的11位手机号码' // 动态替换字段标签
            ],
            [['tags'], 'required', 'message' => '项目标签必须选择', 'on' => ['create', 'update']],
            [['tags'], 'validateTags', 'on' => ['create', 'update']],
        ];
    }

    public function validateTags($attribute, $params)
    {
        if (empty($this->tags) || !is_array($this->tags) || count($this->tags) < 1) {
            $this->addError($attribute, '至少要选择1个项目标签');
        }
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
            'partner_uid' => '合作伙伴',
            'partner_name' => '合作伙伴名称',
            'partner_accept' => '合作伙伴审核',
            'project_name' => '项目名称',
            'manager_uid' => '项目经理UID',
            'manager_name' => '项目经理姓名',
            'bt_manager_uid' => '招投标经理UID',
            'bt_manager_name' => '招投标经理姓名',
            'company_name' => '公司名称',
            'expand_type' => '拓展类型',
            'stars' => '客户星级',
            'tags' => '项目类型',
            'realname' => '客户全名',
            'sex' => '性别',
            'usci_code' => '统一信用代码',
            'job_title' => '职务',
            'phone' => '联系方式1(手机号码)',
            'phone1' => '联系方式2',
            'province' => '省份',
            'city' => '城市',
            'area' => '区域',
            'address' => '联系地址',
            'content' => '项目介绍',
            'steps' => '项目阶段',
            'views' => '查看次数',
            'deleted' => '删除状态',
            'is_demand' => '需求提交',
            'attach_file' => '附件上传',
            'maintain_at' => '最后跟进',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
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
            $source = new CustomerUserMap();
            $source->uid = intval($this->uid);
            $source->project_id = $this->id;
            $source->role_type = System::OWNER;
            $source->save();

            //伙伴
            $target = new CustomerUserMap();
            $target->uid = intval($this->partner_uid);
            $target->project_id = $this->id;
            $target->role_type = System::PARTNER;
            $target->save();
        }
        //关联项目
        UploadFunc::relateProject($this->attach_file, $this->id);
    }

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
        $user = User::findOne($this->partner_uid);
        if ($user) {
            $this->partner_name = $user->username;
        }
        $this->attach_file = str_replace(' ', '', $this->attach_file);
        if (in_array($this->scenario, ['create', 'update'])) {
            $this->tags = TagsFunc::format($this->tags);
        }
        return true;
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
        //删除用户与项目的关联
        CustomerUserMap::deleteAll('project_id=:projectId', ['projectId' => $this->id]);

        //删除需求
        $demands = Demand::find()->where(['project_id' => $this->id])->all();
        if ($demands) {
            foreach ($demands as $demand) {
                //作品删除
                DemandWorks::deleteAll('demand_id=:demandId', ['demandId' => $demand->id]);

                //用户映射删除
                DemandUserMap::deleteAll('demand_id=:demandId', ['demandId' => $demand->id]);
                $demand->delete();
            }
        }

        //删除项目维护
        $manitains = Maintain::find()->where(['project_id' => $this->id])->all();
        if ($manitains) {
            foreach ($manitains as $maintain) {
                //用户映射删除
                MaintainUserMap::deleteAll('maintain_id=:maintainId', ['maintainId' => $maintain->id]);
                $maintain->delete();
            }
        }

        return true;
    }
}
