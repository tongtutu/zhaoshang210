<?php
/**
 * 后台基础类，所有类必须继承
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\common\controllers\app;

use Yii;
use yii\helpers\Url;
use bagesoft\models\User;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\constant\CodeConst;
use yii\web\NotFoundHttpException;

class Base extends \bagesoft\common\controllers\Base
{
    //管理登录信息
    protected $session;
    public $accessList;
    public $user;
    public function init()
    {
        parent::init();
        $this->session = parent::_sessionGet('zhaoshang');

        if (empty($this->session)) {
            $keep = $this->keepLogin();
            if (false == $keep) {
                Yii::$app->response->redirect(Url::toRoute(['/public/login']))->send();
                Yii::$app->end();
                return;
            }
            $this->session = $keep;
        }

        $this->user = User::find()->alias('user')->joinWith('group group')->where('user.id=:id', ['id' => $this->session['uid']])->one();
        if (false == $this->user) {
            Yii::$app->response->redirect(Url::toRoute(['/public/login']))->send();
            Yii::$app->end();
            return;
        }
        $this->accessList = $this->user->group->acl;
    }

    /**
     * 保持登录
     * @return [type] [description]
     */
    private function keepLogin()
    {
        $uid = parent::_cookiesGet('zhaoshang');
        if ($uid) {
            $user = User::findOne($uid);
            if ($user) {
                $values = [
                    'uid' => $user->id,
                    'gid' => $user->gid,
                    'username' => $user->username,
                    'role' => System::USER_ROLE_APP,
                ];
                parent::_sessionSet('zhaoshang', $values);
                parent::_cookiesSet(
                    [
                        'name' => 'zhaoshang',
                        'value' => $user->id,
                        'expire' => time() + 604800,
                    ]
                );
                return $values;
            }
        }
        return false;
    }

    /**
     * 消息输出
     * @param  $method method
     * @param  $data   数据
     * @return
     */
    protected function _renderMessage($method, $data)
    {
        if ($method == 'ajax') {
            parent::_renderJson($data);
        } elseif ($data['code'] == 200 && $data['next']) {
            $this->redirect($data['next']);
        } elseif ($data['code'] == 200) {
            $this->redirect('index');
        } elseif ($data['code'] == -200) {
            throw new NotFoundHttpException($data['message']);
        }
    }

    /**
     * 权限检测
     * @param  string $action 操作名称
     * @param  array  $params 参数
     * @return [type]
     */
    protected function acl($action = '', $params = ['out' => 'page'])
    {
        $action = strtolower($action ? $action : $this->id . '/' . $this->action->id);

        if ($this->session['gid'] > 1) {
            try {
                if (!in_array($action, explode(',', strtolower($this->accessList . $params['append'])))) {
                    throw new \Exception('当前角色组无权限进行此操作，请联系超管授权');
                }
            } catch (\Exception $e) {
                if ($params['out'] == 'text') {
                    exit($e->getMessage());
                } elseif (Yii::$app->request->isAjax) {
                    parent::renderErrorJson($e->getMessage(), null, CodeConst::FAILD_CODE);
                } else {
                    $value = $array['append'] ?? '';
                    throw new \yii\web\ForbiddenHttpException($e->getMessage());
                }
            }
        }
    }

}
