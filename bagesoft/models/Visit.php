<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%visit}}".
 *
 * @property int $id ID
 * @property int $uid 用户UID
 * @property int $visit_source 来源
 * @property int $visit_uid 访问ID
 * @property string $visit_user 访问者信息
 * @property int $project_id 项目ID
 * @property int $project_source 项目来源
 * @property string|null $project_name 项目名称
 * @property int $date_at 发布日期
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class Visit extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%visit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'visit_source', 'visit_uid', 'project_id', 'project_source', 'date_at', 'updated_at', 'created_at'], 'integer'],
            [['visit_user', 'project_source'], 'required'],
            [['visit_user'], 'string', 'max' => 250],
            [['project_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户UID',
            'visit_source' => '来源',
            'visit_uid' => '访问ID',
            'visit_user' => '访问者信息',
            'project_id' => '项目ID',
            'project_source' => '项目来源',
            'project_name' => '项目名称',
            'date_at' => '发布日期',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
