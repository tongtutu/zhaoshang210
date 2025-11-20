<?php
/**
 * 主页
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */
namespace app\controllers;

use bagesoft\common\controllers\Base;
use bagesoft\constant\System;
use bagesoft\functions\HashFunc;
use bagesoft\functions\UserFunc;
use bagesoft\helpers\Utils;
use Yii;

class HashController extends Base
{
    public function actionRequest()
    {
        try {
            $request = Yii::$app->request;
            $mobile = trim($request->get('mobile'));
            $type = trim($request->get('type'));
            $hash = Utils::randStr(4, 1);

            switch ($type) {
                //编辑密码
                case System::HASH_CODE_EDIT_PASS:
                    $session = parent::_sessionGet('zhaoshang');
                    $user = UserFunc::getUserById($session['uid']);
                    if (empty($user->mobile)) {
                        throw new \Exception('手机号未填写');
                    }
                    $mobile = $user->mobile;
                    break;
                //编辑手机
                case System::HASH_CODE_EDIT_MOBILE:
                    if (empty($mobile)) {
                        throw new \Exception('手机号必须提交');
                    } elseif (!Utils::isMobile($mobile)) {
                        throw new \Exception('手机号格式错误');
                    }
                    break;
                default:
                    if (empty($mobile)) {
                        throw new \Exception('手机号必须提交');
                    }
                    $user = UserFunc::getUserByMobile($mobile);
                    if (false == $user) {
                        throw new \Exception('手机号未找到');
                    }
                    break;
            }
            $send = HashFunc::send(
                [
                    'target' => $mobile,
                    '_tpl' => $type,
                    '_tplVar' => [
                        'code' => $hash,
                    ],
                    'hash' => $hash,
                    'limit' => [
                        'condition' => [
                            'token' => $type . '~' . $mobile,
                        ],
                        'timeout' => 60,
                    ],
                ]
            );

            if ($send['state'] == 'error') {
                throw new \Exception($send['message']);
            }
            parent::renderSuccessJson([], '发送成功');

        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

}
