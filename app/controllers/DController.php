<?php
/**
 * 文件
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use bagesoft\helpers\Utils;
use bagesoft\models\Upload;
use bagesoft\functions\UploadFunc;

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

        // Utils::dump($a);


        $upload  = Upload::find()->all();
        foreach ($upload as $v) {
            if (empty($v->keyid)) {
                $v->keyid = Utils::guid(false);
            }

            $v->save();
        }
    }

}
