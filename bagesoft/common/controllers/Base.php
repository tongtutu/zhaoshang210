<?php
/**
 * 控制器基类，前端，后端均需继承此类
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\common\controllers;

use bagesoft\constant\CodeConst;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class Base extends Controller
{

    protected $_response;
    protected $_request;
    protected $_conf;
    protected $_bagecms = '4.0';

    /**
     * 全局方法
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 取session
     */
    protected function _sessionGet($name)
    {
        return Yii::$app->session->get($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 设置session
     */
    protected function _sessionSet($name, $value)
    {
        Yii::$app->session->set($name . '_' . Yii::$app->params['appid'], $value);
    }

    /**
     * 清除session
     */
    protected function _sessionRemove($name)
    {
        Yii::$app->session->remove($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 清除所有session
     */
    protected function _sessionDestroy()
    {
        Yii::$app->session->destroy();
    }

    /**
     * 设置cookies
     */
    protected function _cookiesSet($arrs)
    {
        $arrs['name'] = $arrs['name'] . '_' . Yii::$app->params['appid'];
        Yii::$app->response->cookies->add(new \yii\web\Cookie($arrs));
    }

    /**
     * 读取cookies
     */
    protected function _cookiesGet($name)
    {
        return Yii::$app->request->cookies->getValue($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 清除cookies
     */
    protected function _cookiesRemove($name)
    {
        Yii::$app->response->cookies->remove($name . '_' . Yii::$app->params['appid']);
    }

    /**
     * 输出JSON
     * @param  [type] $array [description]
     * @return [type]        [description]
     */
    protected function _renderJson($array = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $array;
        Yii::$app->end();
    }

    /**
     * 提示消息
     */
    protected function _message($arrs)
    {
        $this->layout = false;
        if (empty($arrs['timeout']) || !isset($arrs['timeout'])) {
            $arrs['timeout'] = 2;
        }
        if (empty($arrs['title']) && $arrs['state'] == 'error') {
            $arrs['title'] = '操作失败';
        } elseif (empty($arrs['title']) && $arrs['state'] == 'success') {
            $arrs['title'] = '操作成功';
        }
        if ($arrs['state'] == '-1') {
            $arrs['url'] = '';
        } elseif ($arrs['state'] == 'redirect') {
            Yii::$app->response->redirect($arrs['url'])->send();
        } elseif ($arrs['state'] == 'script') {
            exit('<script language="javascript">alert("' . $arrs['message'] . '");window.location=" ' . $arrs['url'] . '"</script>');
        }
        return $this->render('@app/views/error/message', $arrs);
    }

    /**
     * 输出JSON
     * @param array $array 数组
     * @return json
     */
    protected function renderJson($array = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $array;
        Yii::$app->end();
    }

    /**
     * JSON格式输出正常结果
     * @param array $data
     * @param string $message
     * @throws \yii\base\ExitException
     */
    protected function renderSuccessJson($data = [], $message = '请求成功')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $resultArray = [
            'state' => 'success',
            'code' => CodeConst::SUCCESS_CODE,
            'message' => $message,
            'timestamp' => time(),
            'data' => $data,
        ];
        Yii::$app->response->data = $resultArray;
        Yii::$app->end();
    }

    /**
     * JSON格式输出失败结果
     * @param $message
     * @param $code code码
     * @param $data
     * @throws \yii\base\ExitException
     */
    protected function renderErrorJson($msg, $data = null, $code = CodeConst::FAILD_CODE)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $resultArray = [
            'state' => 'error',
            'code' => $code,
            'message' => $msg,
            'timestamp' => time(),
            'data' => $data,
        ];
        Yii::$app->response->data = $resultArray;
        Yii::$app->end();
    }
}
