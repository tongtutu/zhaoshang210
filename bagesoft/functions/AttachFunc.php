<?php
/**
 * 附件
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

class AttachFunc
{
    public static function listfile($files, $split = ',')
    {
        if ($files) {
            $filelist = \explode($split, $files);
        } else {
            $filelist = [];
        }

        return $filelist;
    }

}
