<?php
/**
 * 后台基础类，所有类必须继承
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\common\controllers\admin;

use bagesoft\constant\System;
use bagesoft\functions\AdminFunc;
use bagesoft\models\Admin;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class Base extends \bagesoft\common\controllers\Base
{
    //管理登录信息
    protected $session;
    public $accessList;
    public $admin;
    public function init()
    {
        parent::init();
        $this->session = parent::_sessionGet('admin');

        if (empty($this->session)) {
            $keep = $this->keepLogin();
            if (false == $keep) {
                Yii::$app->response->redirect(Url::toRoute(['/public/login']))->send();
                Yii::$app->end();
                return;
            }
            $this->session = $keep;
        }

        $this->admin = Admin::find()->alias('admin')->joinWith('group group')->where('admin.id=:id', ['id' => $this->session['uid']])->one();
        $this->accessList = $this->admin->group->acl;
    }

    /**
     * 保持登录
     * @return [type] [description]
     */
    private function keepLogin()
    {
        $uid = parent::_cookiesGet('admin');
        if ($uid) {
            $admin = Admin::findOne($uid);
            if ($admin) {
                $values = [
                    'uid' => $admin->id,
                    'gid' => $admin->gid,
                    'username' => $admin->username,
                    'role' => System::USER_ROLE_ADMIN,
                ];
                parent::_sessionSet('admin', $values);
                parent::_cookiesSet(
                    [
                        'name' => 'admin',
                        'value' => $admin->id,
                        'expire' => time() + 604800,
                    ]
                );
                return $values;
            }
        }
        return false;
    }

    /**
     * 行为记录
     * @param $action
     * @throws Exception
     * @return boolean
     */
    public function beforeAction($action)
    {
        AdminFunc::log();
        return true;
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
        $params['append'] = isset($params['append']) ? $params['append'] : '';
        if ($this->session['gid'] > 2) {
            try {
                if (!in_array($action, explode(',', strtolower($this->accessList . $params['append'])))) {
                    throw new \Exception('当前角色组无权限进行此操作，请联系超管授权');
                }
            } catch (\Exception $e) {
                if ($params['out'] == 'text') {
                    exit($e->getMessage());
                } elseif (Yii::$app->request->isAjax) {
                    parent::renderErrorJson($e->getMessage());
                } else {
                    throw new \yii\web\ForbiddenHttpException($e->getMessage());
                }
            }
        }
    }
}
