<?php
/**
 * 项目
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\functions;

use bagesoft\constant\UserConst;
use bagesoft\helpers\Session;
use bagesoft\models\Admin;
use bagesoft\models\AdminLog;
use bagesoft\models\AdminMenu;
use Yii;
use yii\helpers\ArrayHelper;

class AdminFunc
{

    /**
     * 日志内容替换
     * @var array
     */
    private static $_traceFilterCol = [
        'Admin' => ['password'],
        'User' => ['password'],
        'Post' => ['content'],
    ];

    /**
     * 行为记录
     * 跳过数据库恢复，会引起重复主键出错
     * @param  [type] $vars [description]
     * @return [type]       [description]
     */
    public static function log($vars = [])
    {
        try {
            $request = Yii::$app->request;
            $session = Session::get('admin');
            $action = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

            if (false == $vars && false == $session) {
                return;
            } elseif ($action == 'database/import' && $request->get('pre')) {
                return;
            }

            if (isset($vars['uid'])) {
                $uid = $vars['uid'];
            } elseif ($session['uid']) {
                $uid = $session['uid'];
            } else {
                $uid = 0;
            }
            if (isset($vars['username'])) {
                $username = $vars['username'];
            } elseif ($session['username']) {
                $username = $session['username'];
            } else {
                $username = 'guest';
            }

            // if ($uid == 1) {
            //     return; !!!debug!!!
            // }

            $trace = new AdminLog();
            $trace->attributes = [
                'method' => $request->method,
                'uid' => intval($uid),
                'username' => $username,
                'action' => isset($vars['action']) ? $vars['action'] : $action,
                'action_url' => isset($vars['action_url']) ? $vars['action_url'] : $request->url,
                'ip' => $request->userIP,
                'user_agent' => $request->headers->has('User-Agent') ? $request->headers->get('User-Agent') : '',
                'datas' => count($_POST) > 0 ? self::_traceFilter($_POST) : '',
                'intro' => isset($vars['intro']) ? $vars['intro'] : '',
                'date_at' => date('Ymd'),
            ];
            if (!$trace->save()) {
                throw new \Exception('写入失败');
            }
        } catch (\Exception $e) {

        }
    }

    /**
     * 日志部分内容过滤
     *
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private static function _traceFilter($data)
    {
        foreach ((array) self::$_traceFilterCol as $key => $val) {
            if (isset($data[$key])) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        if ($data[$key][$v]) {
                            $data[$key][$v] = '***';
                        }
                    }
                } else {
                    if ($data[$key]) {
                        $data[$key] = '***';
                    }
                }
            }
        }
        return serialize($data);
    }

    /**
     * GID获取用户列表
     *
     * @param integer $gid
     * @return array
     */
    public static function getListByGid($gid)
    {
        return ArrayHelper::map(Admin::find()->where('gid=:gid AND state=:state', ['gid' => $gid, 'state' => UserConst::STATUS_ACTIVE])->all(), 'id', 'username');
    }

    /**
     * 获取用户信息
     *
     * @param integer $id
     * @return Admin|null
     */
    public static function getItemById($id)
    {
        return Admin::findOne($id);

    }

    /**
     * 事务日志记录
     * @param $action
     */
    public static function transName($action)
    {
        $action = str_replace('admin/', '', $action);
        $model = AdminMenu::find()->where(['route' => $action])->one();
        if ($model) {
            return $model->title;
        }
    }
}
