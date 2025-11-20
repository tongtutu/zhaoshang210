<?php

namespace bagesoft\models;

use bagesoft\constant\System;
use bagesoft\constant\ProjectConst;
/**
 * This is the model class for table "{{%demand}}".
 *
 * @property int $id ID
 * @property int $demand_id 需求ID
 * @property int $worker_uid 创作者UID
 * @property string $worker_name 创作者名称
 * @property int $worker_accept 创作接受状态
 * @property int $state 状态
 * @property int $worker_state 作品状态
 * @property int $worker_first_at 首次提交
 * @property int $worker_succ_at 完成时间
 * @property int $operator_uid 审批人UID
 * @property string $operator_name 审批人
 * @property int $produce_num 创作次数
 * @property int $is_remind 提醒状态
 * @property int $mtstate 需求关联状态
 */
class DemandTask extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%demand_task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'demand_id', 'worker_uid', 'worker_accept', 'state', 'worker_state', 'worker_first_at', 'worker_succ_at', 'operator_uid', 'produce_num', 'is_remind', 'mtstate'], 'integer'],
            [['worker_name', 'operator_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'demand_id' => '需求ID',
            'worker_uid' => '创作者UID',
            'worker_name' => '创作者名称',
            'worker_accept' => '创作接受状态',
            'state' => '状态',
            'worker_state' => '作品状态',
            'worker_first_at' => '首次提交',
            'worker_succ_at' => '完成时间',
            'operator_uid' => '审批人UID',
            'operator_name' => '审批人',
            'produce_num' => '创作次数',
            'is_remind' => '提醒状态',
            'mtstate' => '需求关联状态',
        ];
    }

    /**
     * 存前操作
     *
     * @param [type] $insert
     * @return bool
     */
    /**
     * 存后操作
     *
     * @param [type] $insert
     * @param [type] $changedAttributes
     * @return void
     */
    

    /**
     * 删前操作
     *
     * @param [type] $insert
     * @param [type] $changedAttributes
     */

}
