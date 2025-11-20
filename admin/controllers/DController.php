<?php
/**
 * 文件
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use bagesoft\functions\UploadFunc;
use bagesoft\helpers\Utils;

class DController extends \bagesoft\common\controllers\Base
{
    /**
     * 获取文件
     *
     * @param mixed $id
     * @return mixed
     */
    public function actionF($id)
    {
        UploadFunc::getFileByUuid($id);
    }

    public function actionTest()
    {
        $a = UploadFunc::getLinkText(6, 21);

        Utils::dump($a);
    }

}
