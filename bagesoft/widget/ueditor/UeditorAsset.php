<?php
/**
 * 百度编辑器
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Widget
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\widget\ueditor;

use yii\web\AssetBundle;

class UeditorAsset extends AssetBundle
{
    public $js = [
        'ueditor.config.js',
        'ueditor.all.min.js',
    ];
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
}
