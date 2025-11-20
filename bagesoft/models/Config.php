<?php
/**
 * Config 表模型
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 * @package       BageCMS.Models
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\models;

use bagesoft\helpers\Cache;

class Config extends \bagesoft\common\models\Base
{
    /**
     * 表名称
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * 模型属性验证规则
     */
    public function rules()
    {
        return [
            [['val', 'autoload'], 'string'],
            [['opt'], 'string', 'max' => 20],
            [['var'], 'string', 'max' => 50],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'opt' => '类型',
            'var' => '变量名',
            'val' => '变量值',
            'autoload' => '自动加载',
            'desc' => '描述',
        ];
    }

    /**
     * 获取数据
     */
    public static function _loadAll($vars = [])
    {
        $model = static::find();
        if (isset($vars['condition'])) {
            $model->where($vars['condition'], $vars['params']);
        }
        $datas = $model->all();
        $conf = [];
        if ($datas) {
            foreach ($datas as $data) {
                $conf[$data->var] = $data->val;
            }
        }
        return $conf;
    }

    /**
     * 缓存配置
     * @return [type] [description]
     */
    public static function _cached()
    {
        $datas = self::_loadAll();
        Cache::set(
            [
                'name' => '_conf',
                'value' => $datas,
                'duration' => 86400,
            ]
        );
    }
}
