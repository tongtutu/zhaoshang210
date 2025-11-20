<?php
/**
 * 缓存
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */
namespace app\controllers;

use bagesoft\helpers\File;
use Yii;

class CacheController extends \bagesoft\common\controllers\app\Base
{
    /**
     * 首页
     * @return [type] [description]
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 清空缓存
     * @return [type] [description]
     */
    public function actionFlush()
    {
        try {
            parent::acl();
            Yii::$app->cache->flush();
            parent::_renderJson(
                [
                    'code' => 200,
                    'message' => '清空完成',
                    'data' => [],
                ]

            );
        } catch (\Exception $e) {
            parent::_renderJson(
                [
                    'code' => -200,
                    'message' => $e->getMessage(),
                    'data' => [],
                ]
            );
        }
    }
    /**
     * 前端资源删除缓存
     * @return [type] [description]
     */
    public function actionClearAssets()
    {
        try {
            parent::acl();
            foreach (glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $asset) {
                if ($asset == '.gitignore') {
                    continue;
                }
                if (is_dir($asset)) {
                    File::delDir($asset);
                } else {
                    unlink($asset);
                }
            }
            parent::_renderJson(
                [
                    'code' => 200,
                    'message' => '清空完成',
                    'data' => [],
                ]
            );

        } catch (\Exception $e) {
            parent::_renderJson(
                [
                    'code' => -200,
                    'message' => $e->getMessage(),
                    'data' => [],
                ]
            );
        }

    }
}
