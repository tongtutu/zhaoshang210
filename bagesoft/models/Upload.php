<?php

namespace bagesoft\models;

use Yii;
use bagesoft\helpers\Utils;
/**
 * This is the model class for table "{{%upload}}".
 *
 * @property int $id 主键
 * @property int $uid 用户名
 * @property string $keyid UUID
 * @property int $source 来源
 * @property int $project_id 项目ID
 * @property string $real_name 原始文件名称
 * @property string $file_name 带路径文件名
 * @property resource $thumb_name 缩略图
 * @property string $save_path 保存路径
 * @property string $save_name 保存文件名不带路径
 * @property string $hash hash
 * @property string $file_ext 扩展名称
 * @property string $file_mime 文件头信息
 * @property int $file_size 文件大小
 * @property int $created_at 入库时间
 */
class Upload extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%upload}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'source', 'project_id', 'file_size', 'created_at'], 'integer'],
            [['keyid'], 'string', 'max' => 64],
            [['real_name', 'file_name', 'thumb_name', 'save_path', 'save_name', 'file_mime'], 'string', 'max' => 255],
            [['hash'], 'string', 'max' => 32],
            [['file_ext'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'uid' => '用户名',
            'keyid' => 'UUID',
            'source' => '来源',
            'project_id' => '项目ID',
            'real_name' => '原始文件名称',
            'file_name' => '带路径文件名',
            'thumb_name' => '缩略图',
            'save_path' => '保存路径',
            'save_name' => '保存文件名不带路径',
            'hash' => 'hash',
            'file_ext' => '扩展名称',
            'file_mime' => '文件头信息',
            'file_size' => '文件大小',
            'created_at' => '入库时间',
        ];
    }

}
