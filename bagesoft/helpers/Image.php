<?php
/**
 * 图像助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\helpers;

class Image
{
    private static $_instance;

    /**
     * 图片水印
     * @param  string $source 水印图片路径
     * @param int     $locate 水印位置
     * @param int     $alpha  透明度
     * @return [type]          [description]
     */
    public static function water($image, $args = '')
    {
        $params = [
            'waterFile' => './static/watermark.png',
            'locate' => 9,
            'alpha' => '100',
        ];
        if (is_array($args)) {
            $params = array_merge($params, $args);
        }
        try {
            self::instance($image);
            self::$_instance->water($params['waterFile'], $params['locate'], $params['alpha'])->save($image);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 文字水印
     * @param  string  $text   添加的文字
     * @param  string  $font   字体路径
     * @param  integer $size   字号
     * @param  string  $color  文字颜色
     * @param int      $locate 文字写入位置
     * @param  integer $offset 文字相对当前位置的偏移量
     * @param  integer $angle  文字倾斜角度
     */
    public static function text($image, $text, $args = '')
    {
        $params = [
            'font' => './static/test.ttf',
            'size' => 25,
            'color' => '#000000',
            'locate' => '9',
            'offset' => '0',
            'angle' => '0',
        ];
        if (is_array($args)) {
            $params = array_merge($params, $args);
        }
        try {
            self::instance($image);
            self::$_instance->text($text, $params['font'], $params['size'], $params['color'], $params['locate'], $params['offset'], $params['angle'])->save($image);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 裁剪图像
     * @param  integer $w      裁剪区域宽度
     * @param  integer $h      裁剪区域高度
     * @param  integer $x      裁剪区域x坐标
     * @param  integer $y      裁剪区域y坐标
     * @param  integer $width  图像保存宽度
     * @param  integer $height 图像保存高度
     * @return [type] [description]
     */
    public static function crop($image, $args = [])
    {
        try {
            $params = [
                'w' => '200',
                'h' => '200',
                'x' => '100',
                'y' => '100',
                'width' => '400',
                'height' => '400',
            ];
            if (is_array($args)) {
                $params = array_merge($params, $args);
            }
            self::instance($image);
            self::$_instance->crop($params['w'], $params['h'], $params['x'], $params['y'], $params['width'], $params['height'])->save($image);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 旋转图像
     * @param int $degrees 顺时针旋转的度数
     * @return [type] [description]
     */
    public static function rotate($image, $degrees)
    {
        try {
            self::instance($image);
            self::$_instance->rotate($degrees)->save($image);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 生成缩略图
     * @param  integer $width  缩略图最大宽度
     * @param  integer $height 缩略图最大高度
     * @param int      $type   缩略图裁剪类型
     * @return [type] [description]
     */
    public static function thumb($image, $args = [])
    {
        try {
            $type = isset($args['type']) ? $args['type'] : 1;
            self::instance($image);
            self::$_instance->thumb($args['width'], $args['height'], $type)->save($image);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 设置驱动
     * @param string $driver
     * @param array $params
     */
    private static function instance($file)
    {
        if (!self::$_instance) {
            self::$_instance = \bagesoft\library\Image::open($file);
        }
    }

}
