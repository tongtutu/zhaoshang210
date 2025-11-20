<?php
/**
 * Post 表模型
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 * @package       BageCMS.Models
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\models;

class Post extends \bagesoft\common\models\Base
{
    /**
     * 表名称
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * 模型属性验证规则
     */
    public function rules()
    {
        return [
            [['user_id', 'date_year', 'date_month', 'hits', 'auto_is', 'commend', 'vote_like', 'image_state', 'top_line', 'reply_count', 'reply_allow', 'sorted', 'allow_reply', 'state', 'updated_at'], 'integer'],
            [['title', 'class'], 'required'],
            [['image_list', 'intro', 'content', 'seo_description', 'css', 'js'], 'string'],
            [['username'], 'string', 'max' => 30],
            [['author', 'title_alias', 'copy_from', 'html_path', 'html_file'], 'string', 'max' => 100],
            [['title', 'title_second', 'title_style', 'title_style_arr', 'topic', 'copy_url', 'redirect', 'tags', 'image', 'image_thumb', 'seo_title', 'seo_keywords', 'acl_view'], 'string', 'max' => 255],
            [['title_alias_hash'], 'string', 'max' => 32],
            [['tpl'], 'string', 'max' => 60],
            [['created_at', 'auto_at'], 'safe'],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'user_id' => '用户',
            'username' => '用户名',
            'author' => '作者',
            'title' => '标题',
            'title_second' => '副标题',
            'title_alias' => '别名 ',
            'title_alias_hash' => '别名hash',
            'title_style' => '标题样式',
            'title_style_arr' => '标题样式序列化',
            'date_year' => '年',
            'date_month' => '月',
            'tpl' => '模板',
            'class' => '分类',
            'topic' => '专题',
            'image_list' => '组图',
            'intro' => '摘要',
            'content' => '内容',
            'copy_from' => '来源',
            'html_path' => 'html路径',
            'html_file' => 'html文件名',
            'copy_url' => '来源url',
            'redirect' => '跳转URL',
            'tags' => 'TAGS',
            'hits' => '点击次数',
            'auto_is' => '自动上线',
            'auto_at' => '自动上线日期',
            'commend' => '推荐',
            'vote_like' => '有用',
            'image' => '封面图片',
            'image_thumb' => '图片缩略图',
            'image_state' => '是否有附件',
            'top_line' => '头条',
            'seo_title' => 'SEO标题',
            'seo_keywords' => 'SEO关键字',
            'seo_description' => 'SEO描述',
            'reply_count' => '回复次数',
            'reply_allow' => '允许评论',
            'css' => 'CSS样式',
            'js' => 'JS代码',
            'sorted' => '排序',
            'acl_view' => '阅读权限',
            'allow_reply' => '允许回复(1所有,2会员)',
            'state' => '新闻状态',
            'updated_at' => '最后更新',
            'created_at' => '入库时间',
        ];
    }

    /**
     * 入库前默认数据写入
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!$this->created_at) {
                    $this->created_at = time();
                }
            }
            if (!is_numeric($this->created_at)) {
                $this->created_at = intval(strtotime($this->created_at));
            }
            if (!is_numeric($this->auto_at)) {
                $this->auto_at = intval(strtotime($this->auto_at));
            }
            $this->date_year = date('Y', $this->created_at);
            $this->date_month = date('m', $this->created_at);
            $this->title_alias = parent::aliasFmt($this->title_alias);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 栏目数组转字符
     */
    public static function classFmt($classes, $isArray = 0)
    {
        if (is_array($classes) && count($classes) <= 0) {
            return '';
        }
        $class = $dot = '';
        foreach ($classes as $row) {
            //取栏目
            $classModel = PostClass::find()->where('id=:id', ['id' => $row])->one();
            if ($classModel) {
                $class .= $dot . $classModel->id . ',' . $classModel->title . ',' . $classModel->title_alias;
                $dot = "\t";
            }
        }
        return $class;
    }

    /**
     * 栏目字符转数组
     */
    public static function classUnfmt($string, $typeof = 'k')
    {
        if (empty($string)) {
            return [];
        } elseif (is_array($string)) {
            return $string;
        }
        $classes = explode("\t", $string);
        foreach ($classes as $key => $val) {
            $class = explode(',', $val);
            if ($typeof == 'k') {
                $classArr[] = $class[0];
            } elseif ($typeof == 'v') {
                $classArr[] = $class[1];
            } elseif ($typeof == 'kv') {
                $classArr[$class[0]] = $class[1];
            } elseif ($typeof == 'kva') {
                $classArr[$class[0]] = [$class[1], $class[2]];
            }
        }
        return $classArr;
    }

    /**
     * Tag格式化
     */
    public static function tagsFmt($tagStr, $isArray = 0)
    {
        $tagFmt = str_replace([" ", "，", "\t"], ['', ',', ''], $tagStr);
        $tagArr = explode(',', $tagFmt);
        if (empty($tagFmt) || !is_array($tagArr) || count($tagArr) == 0) {
            return '';
        }
        $dot = $tags = '';
        foreach ($tagArr as $k => $tag) {
            /*if (empty($tag) || $k >= 5) {
            continue;
            }*/
            $tagModel = PostTags::find()->where('tag_name=:tag_name', ['tag_name' => $tag])->one();
            if (false == $tagModel) {
                $tagModel = new PostTags();
                $tagModel->attributes = [
                    'tag_name' => $tag,
                ];
                $tagModel->save();
            }
            $tags .= $dot . $tagModel->id . ',' . $tag;
            $dot = "\t";
        }
        return $tags;
    }

    /**
     * Tag反格式化
     */
    public static function tagsUnfmt(array $arrs = [])
    {
        if (empty($arrs['tags'])) {
            return;
        }
        $dot = '';
        $tags = [];
        $tagArr = explode("\t", $arrs['tags']);
        foreach ($tagArr as $key => $val) {
            $tagVal = explode(',', $val);
            if ($arrs['typeof'] == 'k') {
                $tags[] = $tagVal[0];
            } elseif ($arrs['typeof'] == 'v') {
                $tags[] = $tagVal[1];
            } elseif ($arrs['typeof'] == 'kv') {
                $tags[$tagVal[0]] = $tagVal[1];
            }
        }
        return $tags;
    }

    /**
     * 后数据处理
     */
    public function afterSave($insert, $changedAttributes)
    {
        // tag处理
        $tags = static::tagsUnfmt([
            'tags' => $this->tags,
            'typeof' => 'k',
        ]);
        PostTags::mapPost([
            'tags' => $tags,
            'post_id' => $this->id,
            'method' => $insert ? 'insert' : 'update',
        ]);

        // 栏目处理
        $classes = static::classUnfmt($this->class, 'k');
        if (is_array($classes) && count($classes) > 0) {
            PostClass::mapPost([
                'classes' => $classes,
                'post_id' => $this->id,
                'method' => $insert ? 'insert' : 'update',
            ]);
        }
        // 专题处理
        Topic::mapData([
            'topics' => $this->topic ? explode(',', $this->topic) : '',
            'data_id' => $this->id,
            'method' => $insert ? 'insert' : 'update',
        ]);
        return true;
    }

    /**
     * 删除前处理
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //删除tag
            PostTagsMap::deleteAll('post_id=:post_id', ['post_id' => $this->id]);
            //删除分类
            PostClassMap::deleteAll('post_id=:post_id', ['post_id' => $this->id]);
            //删除专题
            TopicDataMap::deleteAll('module=:module AND data_id=:data_id', ['module' => 1, 'data_id' => $this->id]);
            //删除评论
            PostReply::deleteAll('post_id=:post_id', ['post_id' => $this->id]);
            //删除相册
            PostAlbum::deleteAll('post_id=:post_id', ['post_id' => $this->id]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 关键字格式化
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public static function kwordFmt($post)
    {
        if ($post->seo_keywords) {
            return $post->seo_keywords;
        } elseif ($post->tags) {
            return @implode(',',
                Post::tagsUnfmt(
                    [
                        'tags' => $post->tags,
                        'typeof' => 'v',
                    ]
                )
            );
        }
    }
}
