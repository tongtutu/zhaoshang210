<?php
/**
 * 文件存储
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\UploadedFile;
use bagesoft\helpers\Utils;
use bagesoft\models\Upload;
use bagesoft\functions\UploadFunc;
use yii\web\NotFoundHttpException;

class UploadController extends \bagesoft\common\controllers\admin\Base
{
    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $model = Upload::find();

        $request = Yii::$app->request;
        $realName = trim($request->get('realName'));

        if ($realName) {
            $model->andWhere(['like', 'real_name', $realName]);
        }

        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all();
        return $this->render(
            'index',
            [
                'count' => $count,
                'pagination' => $pagination,
                'datalist' => $datalist,
            ]
        );
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        parent::acl();
        $id = intval(Yii::$app->request->get('id'));
        $model = $this->findModel($id);
        $model->file_name && @unlink('./' . $model->file_name);
        $model->thumb_name && @unlink('./' . $model->thumb_name);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * 加载模型
     */
    protected function findModel($id)
    {
        if (($model = Upload::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在');
        }
    }

    public function actionFile($name)
    {
        if (empty($name)) {
            $name = 'file';
        }

        $uploadedFile = UploadedFile::getInstanceByName($name);
        $source = intval(Yii::$app->request->post('source'));
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
                //parent::renderJson(['status' => 'success', 'uploadId' => $upload->id, 'filename' => $fileName]);
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
     */
    public function actionGetfile($id)
    {
        UploadFunc::getFileById($id);
    }


    /**
     * 删除文件
     *
     * @param mixed $id
     * @return mixed
     */
    public function actionDeleteFile($fileId)
    {
        $upload = UploadFunc::getItemById($fileId);
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
