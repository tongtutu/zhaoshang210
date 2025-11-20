<?php
/**
 * HashBlacklist 表模型
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 * @package       BageCMS.Models
 * @license       http://www.cookman.cn/license
*/

namespace bagesoft\models;
use Yii;

class HashBlacklist extends \bagesoft\common\models\Base
{
    /**
     * 表名称
     */
    public static function tableName()
    {
        return '{{%hash_blacklist}}';
    }

    /**
     * 模型属性验证规则
     */
    public function rules()
    {
        return [
            [['state', 'created_at'], 'integer'],
            [['action', 'token'], 'string', 'max' => 50]
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => '操作',
            'token' => 'TOKEN',
            'state' => '状态',
            'created_at' => '入库时间',
        ];
    }
}
