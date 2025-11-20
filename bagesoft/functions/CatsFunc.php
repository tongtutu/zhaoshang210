<?php
/**
 * 项目
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

use bagesoft\constant\System;
use bagesoft\models\Cats;

class CatsFunc
{
    /**
     * 列表
     *
     * @param integer $uid
     * @return array
     */
    public static function getListByMod($keyid = System::CATS_KEYID_TAGS)
    {
        return Cats::find()->where('keyid=:keyid', ['keyid' => $keyid])->all();
    }

    public static function getNameById($id)
    {
        
    }
}
