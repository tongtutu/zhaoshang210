<?php

namespace bagesoft\models;

use Yii;
/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $title_second 副标题
 * @property string $title_alias 标题别名
 * @property string $html_path html路径
 * @property string $html_file html文件
 * @property string|null $intro 简单描述
 * @property string $content 内容
 * @property string $seo_title SEO标题
 * @property string $seo_keywords SEO关键字
 * @property string|null $seo_description SEO描述
 * @property string $tpl 模板
 * @property string $image 附件
 * @property string $image_thumb 附件小图
 * @property int $sorted 排序
 * @property string|null $js JS代码
 * @property string|null $css CSS样式
 * @property int $hits 查看次数
 * @property int $state 状态
 * @property int $updated_at 最后更新
 * @property int $created_at 入库时间
 */
class Page extends \bagesoft\common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'title_alias', 'content'], 'required'],
            [['intro', 'content', 'seo_description', 'js', 'css'], 'string'],
            [['sorted', 'hits', 'state', 'updated_at', 'created_at'], 'integer'],
            [['title', 'title_second', 'title_alias', 'html_path', 'html_file', 'image', 'image_thumb'], 'string', 'max' => 100],
            [['seo_title', 'seo_keywords'], 'string', 'max' => 255],
            [['tpl'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '标题',
            'title_second' => '副标题',
            'title_alias' => '标题别名',
            'html_path' => 'html路径',
            'html_file' => 'html文件',
            'intro' => '简单描述',
            'content' => '内容',
            'seo_title' => 'SEO标题',
            'seo_keywords' => 'SEO关键字',
            'seo_description' => 'SEO描述',
            'tpl' => '模板',
            'image' => '附件',
            'image_thumb' => '附件小图',
            'sorted' => '排序',
            'js' => 'JS代码',
            'css' => 'CSS样式',
            'hits' => '查看次数',
            'state' => '状态',
            'updated_at' => '最后更新',
            'created_at' => '入库时间',
        ];
    }
}
