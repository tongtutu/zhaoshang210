<?php
/**
 * AdminMenu 表模型
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\models;

use bagesoft\components\NestedSetBehavior;
use bagesoft\helpers\Cache;
use bagesoft\library\Tree;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%user_menu}}".
 *
 * @property int $id
 * @property int $parent_id 父ID
 * @property int $root 根
 * @property int $lft 左
 * @property int $rgt 右
 * @property int $level 级别
 * @property string $title 名称
 * @property string $icon ICON
 * @property string $route 路由
 * @property int $state 状态
 * @property int $onmenu 显示在菜单
 * @property int $sorted 排序
 * @property int $created_at 入库时间
 */
class UserMenu extends \bagesoft\common\models\Base
{
    public $depath;
    public static $accessAdd = ',site/index,public/logout';
    public function behaviors()
    {
        return [
            [
                'class' => NestedSetBehavior::className(),
                'hasManyRoots' => false,
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * 表名称
     */
    public static function tableName()
    {
        return '{{%user_menu}}';
    }

    /**
     * 模型属性验证规则
     */
    public function rules()
    {
        return [
            [['parent_id', 'root', 'lft', 'rgt', 'level', 'state', 'onmenu', 'sorted', 'created_at'], 'integer'],
            [['title', 'icon', 'route'], 'string', 'max' => 64],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '父ID',
            'root' => '根',
            'lft' => '左',
            'rgt' => '右',
            'level' => '级别',
            'title' => '名称',
            'icon' => 'ICON',
            'route' => '路由',
            'state' => '状态',
            'onmenu' => '显示在菜单',
            'sorted' => '排序',
            'created_at' => '入库时间',
        ];
    }

    /**
     * 重建缓存
     * @return array 后台菜单目录
     */
    public static function cached()
    {
        $menu = static::find()->asArray()->orderBy('lft ASC')->all();
        $tree = new Tree();
        $tree->init($menu);
        $datalist = $tree->get_tree_array(1, '');
        Cache::set(
            [
                'name' => 'user_menu',
                'value' => $datalist,
                'duration' => 86400,
            ]
        );
        return $datalist;
    }

    /**
     * 生成权限控制树结构
     * @param  string $acl 权限
     * @return array
     */
    public static function tree($acl = '')
    {
        $aclFmt = explode(',', $acl . self::$accessAdd);
        $menuCache = Cache::get('user_menu');
        if ($menuCache) {
            $datalist = $menuCache;
        } else {
            $datalist = self::cached();
        }

        $tree = [];
        foreach ($datalist as $key => $row) {
            $treeSub = self::treeSub($row, $acl, $aclFmt);
            $tree[$key] = [
                'title' => $row['title'],
                'icon' => $row['icon'],
                'route' => $row['route'],
                'onmenu' => $row['onmenu'],
                'access_num' => $treeSub['access_num'],
                'access_route' => $treeSub['access_route'],
                'child' => $treeSub['tree'],
            ];
            if (!in_array($row['route'], $aclFmt) && $acl != 'administrator') {
                unset($tree[$key]);
            }
        }
        return $tree;
    }

    /**
     * 带权限控制子目录树
     * @param  array $array
     * @param  string $acl
     * @param  array $aclFmt
     * @return array
     */
    public static function treeSub($array, $acl, $aclFmt = [])
    {
        $arr[] = $array['route'];
        $tree = [];
        if (count($array['child']) > 0) {
            foreach ($array['child'] as $key => $row) {
                $route = explode('/', $row['route']);
                unset($route[count($route) - 1]);
                $route = implode('/', $route);
                $arr[] = $route;
                $tree[$key] = [
                    'title' => $row['title'],
                    'icon' => $row['icon'],
                    'route' => $row['route'],
                    'onmenu' => $row['onmenu'],
                ];
                if (!in_array($row['route'], $aclFmt) && $acl != 'administrator') {
                    unset($tree[$key]);
                }
            }
        }
        return [
            'access_num' => count($arr),
            'access_route' => array_unique($arr),
            'tree' => $tree,
        ];
    }
}
