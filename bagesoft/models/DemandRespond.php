<?php

namespace bagesoft\models;

use Yii;

/**
 * This is the model class for table "{{%demand_respond}}".
 *
 * @property int $id ID
 * @property int $uid 用户ID
 * @property int $demand_id 需求ID
 * @property int $project_id 项目ID
 * @property int $partner_uid 创作者UID
 * @property string $partner_name 创作者姓名
 * @property string|null $content 回复内容
 * @property string $attach_file 附件上传
 * @property int $state 审核状态
 * @property int $respond_num 提交次数
 * @property int $pass_at 通过时间
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class DemandRespond extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%demand_respond}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'attach_file', 'respond_num'], 'required'],
            [['uid', 'demand_id', 'project_id', 'partner_uid', 'state', 'respond_num', 'pass_at', 'updated_at', 'created_at'], 'integer'],
            [['content', 'attach_file'], 'string'],
            [['partner_name'], 'string', 'max' => 50],
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
            'demand_id' => '需求ID',
            'project_id' => '项目ID',
            'partner_uid' => '创作者UID',
            'partner_name' => '创作者姓名',
            'content' => '回复内容',
            'attach_file' => '附件上传',
            'state' => '审核状态',
            'respond_num' => '提交次数',
            'pass_at' => '通过时间',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
