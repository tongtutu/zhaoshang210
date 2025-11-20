<?php
/**
 * 项目
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\functions;

use bagesoft\models\Tags;

class TagsFunc
{
    /**
     * Tag格式化
     */
    public static function format($tags)
    {
        if (is_array($tags) && count($tags) <= 0) {
            return '';
        }
        $dot = $tagstr = '';
        foreach ($tags as $k => $tag) {
            $tagModel = Tags::find()->where('id=:id', ['id' => $tag])->limit(1)->one();
            if ($tagModel) {
                $tagstr .= $dot . $tagModel->id . ',' . $tagModel->tag_name;
                $dot = "\t";
            }

        }
        return $tagstr;
    }

    /**
     * Tag反格式化
     */
    public static function unformat($tags, $typeof = 'k')
    {
        $tagArr = explode("\t", $tags);
        $result = [];
        foreach ($tagArr as $key => $val) {
            $class = explode(',', $val);
            if ($typeof == 'k') {
                $result[] = $class[0];
            } elseif ($typeof == 'v') {
                $result[] = $class[1];
            } elseif ($typeof == 'kv') {
                $result[$class[0]] = $class[1];
            } elseif ($typeof == 'kva') {
                $result[$class[0]] = [$class[1], $class[2]];
            }
        }
        return $result;
    }

    /**
     * tags渲染
     *
     * @param array $data
     * @return string
     */
    public static function render($data)
    {
        if (empty($data)) {
            return '';
        }
        $array = self::unformat($data, 'v');
        $colors = [
            'red',
            'green',
            'blue',
            'orange',
            'purple',
            'cyan',
            'magenta',
            'lime',
            'aqua',
            'fuchsia',
            'silver',
            'gray',
            'olive',
            'maroon',
            'navy',
            'teal',
            'indigo',
            'violet',
            'pink',
            'brown'
        ];
        $result = '';
        foreach ($array as $index => $value) {
            // 计算当前索引对应的颜色
            $color = $colors[$index % count($colors)];
            // 为避免潜在的XSS攻击，确保输出内容被正确转义
            $escapedTag = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $result .= "<span style='color: $color;'>$escapedTag</span> ";

        }
        return $result;
    }

    /**
     * 获取tags列表
     * @param mixed $catid
     * @return array
     */
    public static function getTagsList($catid = 5)
    {
        $tags = Tags::find()->where('catid=:catid', ['catid' => $catid])->all();
        $result = [];
        foreach ($tags as $tag) {
            $result[$tag->id] = $tag->tag_name;
        }
        return $result;
    }

    /**
     * 获取tags名称
     *
     * @param mixed $tagid
     * @return string
     */
    public static function getTagsName($tagid)
    {
        $tag = Tags::find()->where('id=:id', ['id' => $tagid])->limit(1)->one();
        if ($tag) {
            return $tag->tag_name;
        }
        return '';
    }
}
