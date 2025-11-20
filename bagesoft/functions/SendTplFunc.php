<?php
/**
 * SendTpl 功能类
 * @author        shuguang <5565907@qq.com>
 */

namespace bagesoft\functions;

use bagesoft\models\SendTpl;

class SendTplFunc
{
    /**
     * 模板参数替换
     * @param array $vars
     * @return boolean
     */
    public static function paramsReplace($vars, $scope = ['text', 'html'])
    {
        $tpl = SendTpl::find()->where('title_alias=:title_alias', ['title_alias' => $vars['_tpl']])->limit(1)->one();
        if ($tpl && isset($vars['_tplVar']) && count($vars['_tplVar']) > 0) {
            foreach ((array) $vars['_tplVar'] as $search => $replace) {
                $newSearch[] = '#' . $search . '#';
                $newReplace[] = $replace;
            }
            return [
                'title' => $tpl->title_send,
                'content' => in_array('text', $scope) ? str_replace($newSearch, $newReplace, $tpl->content_text) : '',
                'content_html' => in_array('html', $scope) ? str_replace($newSearch, $newReplace, $tpl->content_html) : '',
                'extend' => self::extendFmt($tpl->extend),
            ];
        } elseif ($tpl) {
            return [
                'title' => $tpl->title_send,
                'content' => in_array('text', $scope) ? $tpl->content_text : '',
                'content_html' => in_array('html', $scope) ? $tpl->content_html : '',
                'extend' => self::extendFmt($tpl->extend),
            ];
        } else {
            return false;
        }
    }

    /**
     * 扩展参数格式化
     * @return array
     */
    private static function extendFmt($string)
    {
        $array = [];
        if (empty($string)) {
            return $array;
        }
        $lineFmt = explode("\r\n", $string);
        foreach ($lineFmt as $line) {
            $rowFmt = explode('~', $line);
            $array[$rowFmt[0]] = $rowFmt[1];
        }
        return $array;
    }
}
