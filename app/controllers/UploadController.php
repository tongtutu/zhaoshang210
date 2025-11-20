<?php
/**
 * 会员
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use bagesoft\functions\UploadFunc;
use bagesoft\helpers\Utils;
use Yii;
use yii\web\UploadedFile;

class UploadController extends \bagesoft\common\controllers\app\Base
{
    public function actionFile($name)
    {
        if (empty($name)) {
            $name = 'file';
        }

        $uploadedFile = UploadedFile::getInstanceByName($name);
        $source = intval(Yii::$app->request->post('source'));
        //Utils::dump($uploadedFile);
        if ($uploadedFile) {
            $path = $this->getPath();
            $file = $this->getName($uploadedFile);
            $fileName = $path['relative'] . $file;
            $saveName = $path['absolute'] . $file;

            if ($uploadedFile->saveAs($saveName)) {
                $upload = UploadFunc::save(
                    [
                        'uid' => intval($this->session['uid']),
                        'source' => $source,
                        'real_name' => $uploadedFile->name,
                        'file_name' => $fileName,
                        'file_ext' => $uploadedFile->getExtension(),
                        'file_mime' => $uploadedFile->type,
                        'file_size' => $uploadedFile->size,
                        'save_path' => $path['relative'],
                    ]
                );


                parent::renderJson(['status' => 'success', 'fileId' => $upload->id, 'filename' => $fileName, 'realName' => $uploadedFile->name]);
            }
        }
        parent::renderJson(['status' => 'error', 'message' => 'Failed to save the uploaded file.']);
    }
    /**
     * 文件保存路径
     *
     * @param mixed $type
     * @return array
     */
    private function getPath($type = 0)
    {
        $resourcePath = Yii::getAlias('@resource');
        switch ($type) {
            case 1:
                $path = date('/Ymd/');
                break;
            case 2:
                $path = date('/Y/m/');
                break;
            default:
                $path = date('/Y/m/d/');
                break;
        }
        $absolutePath = $resourcePath . '/uploads' . $path;

        $relativePath = 'uploads' . $path;
        if (!file_exists($absolutePath) && !is_dir($absolutePath)) {
            mkdir($absolutePath, 0777, \true);
        }
        return [
            'relative' => $relativePath,
            'absolute' => $absolutePath,
        ];
    }
    /**
     * 生成文件名
     * @param mixed $uploadedFile
     * @return string
     */
    private function getName($uploadedFile)
    {
        return \md5(time() . Utils::randStr(10)) . '.' . $uploadedFile->getExtension();
    }

    /**
     * 获取文件
     *
     * @param mixed $id
     * @return mixed
     */
    public function actionGetfile($id)
    {
        $upload = UploadFunc::getFileById($id);
    }

    /**
     * 删除文件
     *
     * @param mixed $id
     * @return mixed
     */
    public function actionDeleteFile($fileId)
    {
        $upload = UploadFunc::getItemByUid($fileId, $this->session['uid']);
        if ($upload) {
            $resourcePath = Yii::getAlias('@resource');
            $absolutePath = $resourcePath . '/' . $upload->file_name;
            @unlink($absolutePath);
            $upload->delete();

            parent::renderSuccessJson(['fileId' => $fileId], '删除完成');
            return;
        } else {
            parent::renderErrorJson('文件不存在或不属于当前用户');
        }
    }
}
