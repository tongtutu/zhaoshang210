<?php
/**
 * 系统首页
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */
namespace admin\controllers\developer;

use bagesoft\communication\Dccomm;
use bagesoft\constant\MessageQueueConst;
use bagesoft\helpers\Utils;

class TestController extends \bagesoft\common\controllers\admin\Base
{

    public function actionIndex()
    {
        $result = (new Dccomm([
            'taskName' => '执行场景',
            'act' => MessageQueueConst::PROJECT_CREATE,
         
        ]))->run([
            'command' => ['a' => 'aa', 'b' => 'bb'],
        ]);

        Utils::dump($result);
    }
}
