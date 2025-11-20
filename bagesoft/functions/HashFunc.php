<?php
/**
 * Hash 功能类
 * @author        shuguang <5565907@qq.com>
 *
 */

namespace bagesoft\functions;

use bagesoft\helpers\Utils;
use bagesoft\library\Email;
use bagesoft\library\Sms;
use bagesoft\models\Hash;
use bagesoft\models\HashCounter;
use bagesoft\models\HashLimit;
use Yii;
use yii\helpers\ArrayHelper;

/*
$hash = Utils::randStr(4, 1);
$mobile = '15158071768';
$code = '7920';
$type = 'login';
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

$token = $type . '~' . $mobile;
$send = HashFunc::test(['token' => $token, 'hash' => $code]);
 */
class HashFunc
{
    /**
     * 发送信息
     * @param array $vars
     * @param string $mod
     * @throws \Exception
     * @return array
     */
    public static function send(array $vars, $mod = 'sms')
    {
        try {
            //限制检测，跳过管理员
            if (!isset($vars['isadmin'])) {
                self::limit($vars['target']);
            }
            $token = '';
            $arrs = [];
            if (isset($vars['token'])) {
                $token = $vars['token'];
            } else {
                $token = $vars['_tpl'] . '~' . $vars['target'];
            }
            if (isset($vars['limit']) && !isset($vars['isadmin'])) {
                //频率限制
                self::last($vars['limit']);
                //次数限制
                self::nums($vars['limit']);
            }
            $arrs['token'] = $token;
            if (isset($vars['hash'])) {
                $arrs['hash'] = $vars['hash'];
            } else {
                $arrs['hash'] = Utils::randStr(4, 1);
            }
            if (isset($vars['expire_at'])) {
                $arrs['expire_at'] = $vars['expire_at'];
            } else {
                $arrs['expire_at'] = time() + 600;
            }
            $vars = ArrayHelper::merge($vars, $arrs);
            //先写db记录，成功后再发送，以免发送成功但数据未写入导致验证错误
            self::todb($vars);
            if ($mod == 'sms') {
                $obj = new Sms();
                $accArgs = [];
                if (isset($vars['sendAcc']) && $vars['sendAcc']) {
                    $accArgs = $vars['sendAcc'];
                }
                $send = $obj->send($vars, $accArgs);
            } else {
                $obj = new Email();
                $send = $obj->send($vars);
            }
            if (!is_array($send)) {
                throw new \Exception($send);
            }
            return [
                'state' => 'success',
                'token' => 1,
                'message' => '发送成功',
            ];
        } catch (\Exception $e) {
            return [
                'state' => 'error',
                'token' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 验证码校验
     * @param array $wheres
     * 1:有效  2：已使用  3：已过期
     */
    public static function test(array $wheres = [])
    {
        try {
            $tokenExp = explode('~', $wheres['token']);
            if (count($wheres) < 1) {
                throw new \Exception('参数必须提交');
            }
            //检测受限
            self::limit($tokenExp[1]);
            $dot = $where = '';
            $timestamp = time();
            foreach ($wheres as $key => $val) {
                $where .= $dot . '`' . $key . '`=:' . $key;
                $params[$key] = $val;
                $dot = ' AND ';
            }
            $hash = Hash::find()->where($where, $params)->limit(1)->one();
            if (false == $hash) {
                //错误记数器
                self::counter($tokenExp[1]);
                throw new \Exception('验证码错误或已失效');
            } elseif ($hash->state == 2) {
                throw new \Exception('验证码已失效，请重新获取');
            } elseif ($hash->expire_at < $timestamp) {
                $hash->state = 3;
                if (!$hash->save()) {
                    throw new \Exception('db保存失败。state=3');
                }
                throw new \Exception('验证码已失效');
            } else {
                return 'success';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 设置验证码过期
     * @param string $token 查询值
     */
    public static function setExpire($token, $code)
    {
        $timestamp = time();
        $hash = Hash::find()->where('token=:token AND hash=:hash', ['token' => $token, 'hash' => $code])->limit(1)->one();
        if ($hash && $hash->state == 1) {
            $hash->state = 2;
            $hash->used_at = $timestamp;
            if (!$hash->save()) {
                throw new \Exception('db保存失败.state=2');
            }
        }
    }

    /**
     * 错误计数
     */
    private static function counter($target)
    {
        $timestamp = time();
        $limitTime = 300; //时间限制
        $maxError = 15; //错误次数
        $counter = HashCounter::find()->where('target=:target', ['target' => $target])->limit(1)->one();
        $update = false;
        if (false == $counter) {
            $counter = new HashCounter();
            $counter->target = $target;
            $counter->error_count = 1;
            $counter->expire_at = $timestamp + $limitTime;
            $update = true;
        } elseif ($counter->expire_at <= $timestamp) {
            //超出限制时间，用当前时间初始化
            $counter->error_count = 1;
            $counter->expire_at = $timestamp + $limitTime;
            $update = true;
        } elseif ($counter->expire_at > $timestamp && $counter->error_count < $maxError) {
            $counter->error_count = $counter->error_count + 1;
            $update = true;
        } elseif ($counter->expire_at > $timestamp && $counter->error_count >= $maxError) {
            //10分钟内错误次数大于100直接进入限制区域
            $limit = HashLimit::find()->where('target=:target', ['target' => $target])->limit(1)->one();
            if (false == $limit) {
                $limit = new HashLimit();
            }
            $limit->attributes = [
                'target' => $target,
                'typed' => '2',
                'expire_at' => $timestamp + $limitTime,
            ];
            $counter->delete();
            if (!$limit->save()) {
                throw new \Exception('HashLimit Write Error');
            }
        }
        if ($update) {
            $counter->save();
        }
    }

    /**
     * 最后一次发送
     * @param  $limit
     */
    private static function last($limit = null)
    {
        $timestamp = time();
        $where = $dot = '';
        $params = [];
        foreach ($limit['condition'] as $key => $val) {
            $where .= $dot . '`' . $key . '`=:' . $key;
            $params[$key] = $val;
            $dot = ' AND ';
        }
        $hash = Hash::find()->where($where, $params)->limit(1)->orderBy('id DESC')->one();
        if ($hash && ($timestamp - $hash->created_at) < $limit['timeout']) {
            throw new \Exception($limit['timeout'] - ($timestamp - $hash->created_at) . '秒后再次提交');
        }
    }

    /**
     * 次数限制
     * @param  $limit
     */
    private static function nums($limit = null)
    {
        $token = $limit['condition']['token'];
        $ip = Yii::$app->request->userIP;
        $startTime = strtotime(date('Ymd'));
        $endTime = $startTime + 86399;
        $mobCount = Hash::find()->where('token=:token AND created_at>=:start AND created_at<=:end', ['token' => $token, 'start' => $startTime, 'end' => $endTime])->count();
        if ($mobCount > 10) {
            throw new \Exception('今日发送次数已达上限');
        }
        $ipCount = Hash::find()->where('ip=:ip AND created_at>=:start AND created_at<=:end', ['ip' => $ip, 'start' => $startTime, 'end' => $endTime])->count();
        if ($ipCount > 20) {
            throw new \Exception('今日发送次数已达上限');
        }
    }

    /**
     * 受限检测
     * 检测是否受限及类型
     * 临时受限到期后删除记录
     * @param string $vars
     */
    public static function limit($target)
    {
        $timestamp = time();
        $limit = HashLimit::find()->where('`target`=:target', ['target' => $target])->all();
        foreach ((array) $limit as $row) {
            $wait = $row->expire_at - $timestamp;
            if ($row->typed == 1) {
                throw new \Exception('该用户已受限');
            } elseif ($row->typed == 2 && $row->expire_at > $timestamp) {
                throw new \Exception('请  ' . $wait . ' 秒后再试');
            } elseif ($row->typed == 2 && $row->expire_at <= $timestamp) {
                $row->delete();
            }
        }
    }

    /**
     * 写Hash记录
     * @param array $vars
     */
    private static function todb($vars)
    {
        $model = new Hash();
        foreach ($vars as $key => $val) {
            if (in_array($key, $model->attributes())) {
                if ($key == 'args') {
                    $model->$key = Utils::jsonStr($val);
                } else {
                    $model->$key = $val;
                }
            }
        }
        $ip = Yii::$app->request->userIP;
        $model->ip = $ip;
        if (!$model->save()) {
            throw new \Exception('todb error');
        } else {
            return true;
        }
    }
}
