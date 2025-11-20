<?php
/**
 * 错误提示
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2099 bagesoft. All rights reserved.
 * @license       http://www.cookman.cn/license
 */
namespace app\controllers;

use Yii;

class ErrorController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 输出错误
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('index',
                [
                    'code' => $exception->statusCode,
                    'title' => $title,
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }
}
