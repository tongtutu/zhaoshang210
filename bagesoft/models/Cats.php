<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%cats}}".
 *
 * @property int $id ID
 * @property string $cat_name 名称
 * @property string $keyid 类型
 * @property int $updated_at 更新时间
 * @property int $created_at 入库时间
 */
class Cats extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cats}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_name'], 'required'],
            [['updated_at', 'created_at'], 'integer'],
            [['cat_name'], 'string', 'max' => 50],
            [['keyid'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_name' => '名称',
            'keyid' => '类型',
            'updated_at' => '更新时间',
            'created_at' => '入库时间',
        ];
    }
}
