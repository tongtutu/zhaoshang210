<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%send_tpl}}".
 *
 * @property int $id 主键
 * @property string $title 模板名称
 * @property string|null $title_send 发送标题
 * @property string $title_alias 模板别名
 * @property string|null $content_text 内容_文本
 * @property string|null $content_html 内容_html
 * @property string|null $extend 扩展内容
 * @property int $sorted 排序
 * @property string|null $intro 备注
 * @property int $updated_at 最后更新
 * @property int $created_at 入库时间
 */
class SendTpl extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%send_tpl}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content_text', 'content_html', 'extend', 'intro'], 'string'],
            [['sorted', 'updated_at', 'created_at'], 'integer'],
            [['title', 'title_send'], 'string', 'max' => 100],
            [['title_alias'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '模板名称',
            'title_send' => '发送标题',
            'title_alias' => '模板别名',
            'content_text' => '内容_文本',
            'content_html' => '内容_html',
            'extend' => '扩展内容',
            'sorted' => '排序',
            'intro' => '备注',
            'updated_at' => '最后更新',
            'created_at' => '入库时间',
        ];
    }
}
