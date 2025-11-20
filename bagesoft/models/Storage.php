<?php
/**
 * Storage 表模型
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 * @package       BageCMS.Models
 * @license       http://www.cookman.cn/license
*/

namespace bagesoft\models;

use Yii;

class Storage extends \bagesoft\common\models\Base
{
    /**
     * 表名称
     */
    public static function tableName()
    {
        return '{{%storage}}';
    }

    /**
     * 模型属性验证规则
     */
    public function rules()
    {
        return [
            [['user_id', 'folder', 'file_size', 'down_count', 'created_at'], 'integer'],
            [['module'], 'string'],
            [['real_name', 'thumb_name', 'access'], 'string', 'max' => 255],
            [['file_name', 'save_path', 'save_name'], 'string', 'max' => 100],
            [['hash'], 'string', 'max' => 32],
            [['file_ext'], 'string', 'max' => 5],
            [['file_mime'], 'string', 'max' => 50]
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'user_id' => '用户名',
            'module' => '范围',
            'folder' => '文件夹',
            'real_name' => '原始文件名称',
            'file_name' => '带路径文件名',
            'thumb_name' => '缩略图',
            'save_path' => '保存路径',
            'save_name' => '保存文件名不带路径',
            'hash' => 'hash',
            'file_ext' => '扩展名称',
            'file_mime' => '文件头信息',
            'file_size' => '文件大小',
            'down_count' => '下载次数',
            'access' => '权限控制',
            'created_at' => '入库时间',
        ];
    }
}
