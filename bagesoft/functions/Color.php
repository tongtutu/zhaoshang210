<?php
/**
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

class Color
{

    const HOT = [
        1 => 'c-red',
        2 => 'c-yellow',
        3 => 'c-blue',
        4 => 'c-green',
        5 => 'c-purple',
    ];

    public static function set($num)
    {
        if (array_key_exists($num, self::HOT)) {
            return self::HOT[$num];
        }
    }

}
