<?php
/**
 * 基础类
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 * @package       BageCMS.Model
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\common\models;

use bagesoft\helpers\Tools;
use Yii;
use yii\web\NotFoundHttpException;

class Base extends \yii\db\ActiveRecord
{
    public $verify_code;
    public $_message;
    public static function findByPk($id)
    {

        if (($model = self::findOne($id)) !== null) {
            return $model;
        } else {
            $model = str_replace('bagesoft\models\\', '', get_called_class());
            if (Yii::$app->request->isAjax) {
                throw new \Exception('所请求的记录不存在. [' . $model . ']');
            } else {
                throw new NotFoundHttpException('所请求的记录不存在. [' . $model . ']');
            }
        }
    }

    /**
     * 别名格式化
     */
    public function aliasFmt($alias)
    {
        return str_replace([' ', ',', '/'], ['-', '', '-'], trim(strtolower($alias)));
    }

    /**
     * model 错误信息捕获
     * @param  boolean $first [description]
     * @return [type]         [description]
     */
    public function _errors($first = true)
    {
        foreach ((array) $this->firstErrors as $key => $error) {
            if ($first) {
                return $error;
            }
            $arrs[] = $error;
        }
        return implode(' ', $arrs);
    }

    /**
     * 入库前默认数据写入
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                in_array('created_at', $this->attributes()) && $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 全局数据录入
     * @param array $arr 数据
     * @return mixed
     */
    public static function addNew(array $arr, $showError = false)
    {
        try {
            $called = '\\' . get_called_class();
            $model = new $called();
            foreach ($arr as $key => $val) {
                if (in_array($key, $model->attributes())) {
                    $model->$key = $val;
                }
            }
            if ($model->save()) {
                return $model;
            } else {
                $error = array_values($model->firstErrors);
                throw new \Exception('操作未成功。' . $error[0]);
            }
        } catch (\Exception $e) {
            Tools::debug(array_merge($arr, ['class' => get_class($model), 'msg' => $error ? $error : $e->getMessage()]));
            //throw new \Exception('操作未成功。Code:0x0010001');
            throw new \Exception($e->getMessage());
        }
    }
}
