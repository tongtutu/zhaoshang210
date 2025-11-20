<?php

namespace bagesoft\models;
/**
 * This is the model class for table "{{%tags}}".
 *
 * @property int $id ID
 * @property int $catid 分类
 * @property string $tag_name tag名称
 * @property int $counts 数据总数
 * @property int $created_at 入库时间
 */
class Tags extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['catid', 'counts', 'created_at'], 'integer'],
            [['tag_name'], 'required'],
            [['tag_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'catid' => '分类',
            'tag_name' => 'tag名称',
            'counts' => '数据总数',
            'created_at' => '入库时间',
        ];
    }

}
