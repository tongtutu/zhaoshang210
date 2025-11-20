<?php

namespace bagesoft\models;

use bagesoft\helpers\Utils;
use Yii;
use bagesoft\functions\UploadFunc;
/**
 * This is the model class for table "{{%demand_works}}".
 *
 * @property int $id ID
 * @property int $uid 用户ID
 * @property int $partner_uid 需求所有者UID
 * @property string $partner_name 需求所有者姓名
 * @property int $worker_uid 创作者UID
 * @property string $worker_name 创作者名称
 * @property int $demand_id 需求ID
 * @property int $project_id 项目ID
 * @property string|null $content 创作说明
 * @property string|null $reply_content 审核回复
 * @property string $attach_file 作品上传
 * @property int $state 审核结果
 * @property string $hope_time 期望提交时间
 * @property int $hope_at 期望时间戳
 * @property int $produce_num 创作次数
 * @property int $audit_uid 审核UID
 * @property string $audit_name 审核用户
 * @property string $audit_file 审核回复附件
 * @property int $audit_at 通过时间
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class DemandWorks extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%demand_works}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'attach_file', 'produce_num'], 'required'],
            [['uid', 'partner_uid', 'worker_uid', 'demand_id', 'project_id', 'state', 'hope_at', 'produce_num', 'audit_uid', 'audit_at', 'updated_at', 'created_at'], 'integer'],
            [['content', 'reply_content', 'attach_file'], 'string'],
            [['partner_name', 'worker_name', 'audit_name'], 'string', 'max' => 50],
            [['hope_time'], 'string', 'max' => 30],
            [['audit_file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户ID',
            'partner_uid' => '需求所有者UID',
            'partner_name' => '需求所有者姓名',
            'worker_uid' => '创作者UID',
            'worker_name' => '创作者名称',
            'demand_id' => '需求ID',
            'project_id' => '项目ID',
            'content' => '创作说明',
            'reply_content' => '审核回复',
            'attach_file' => '作品上传',
            'state' => '审核结果',
            'hope_time' => '期望提交时间',
            'hope_at' => '期望时间戳',
            'produce_num' => '创作次数',
            'audit_uid' => '审核UID',
            'audit_name' => '审核用户',
            'audit_file' => '审核回复附件',
            'audit_at' => '通过时间',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
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
            //关联项目
            UploadFunc::relateProject($this->attach_file, $this->id);
        } else {
            //关联项目
            UploadFunc::relateProject($this->audit_file, $this->id);
        }
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
            $this->attach_file = str_replace(' ', '', $this->attach_file);
            $this->audit_file = str_replace(' ', '', $this->audit_file);
            return true;
        }
        return false;
    }
}
