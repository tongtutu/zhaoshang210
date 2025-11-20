<?php
/**
 * 上传文件
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\functions;

use Yii;
use yii\helpers\Url;
use bagesoft\helpers\Utils;
use bagesoft\models\Upload;

class UploadFunc
{
    /**
     * 保存数据
     *
     * @param array $data
     * @return object
     */
    public static function save($data)
    {
        $model = new Upload();
        $model->keyid = Utils::guid(false);
        $model->attributes = $data;
        $model->save();
        return $model;
    }

    /**
     * ID取记录
     *
     * @param integer $id
     * @return mixed
     */
    public static function getItemById($id)
    {
        return Upload::findOne($id);
    }

    /**
     * ID,UID取记录
     *
     * @param integer $id
     * @param integer $uid
     * @return mixed
     */
    public static function getItemByUid($id, $uid)
    {
        return Upload::find()->where('id=:id AND uid=:uid', ['uid' => $uid, 'id' => $id])->limit(1)->one();
    }

    /**
     * ID,UID取记录
     *
     * @param integer $id
     * @param integer $uid
     */
    public static function getItemByUuid($uuid)
    {
        return Upload::find()->where('keyid=:keyid', ['keyid' => $uuid])->limit(1)->one();
    }

    /**
     * 附件关联项目
     * @param mixed $uploadIds
     * @param mixed $projectId
     * @return void
     */
    public static function relateProject($uploadIds, $projectId)
    {
        if (empty($uploadIds)) {
            return;
        }
        // 将字符串分割成数组
        $files = explode(',', $uploadIds);

        // 过滤掉非数字的值
        $numericFiles = array_filter($files, function ($value) {
            // 去除首尾空格并检查是否为数字
            return is_numeric(trim($value));
        });

        // 如果过滤后数组为空，则说明没有有效的ID，直接返回
        if (empty($numericFiles)) {
            // （可选）你可以在这里记录一条日志，说明提供的uploadIds无效
            // error_log("relateProject: 在 uploadIds 中没有找到有效的数字ID: " . $uploadIds);
            return;
        }

        // 使用过滤后的有效数字ID执行更新操作
        Upload::updateAll(['project_id' => $projectId], ['in', 'id', $numericFiles]);

    }

    /**
     * 获取附件列表
     * @param mixed $source
     * @param mixed $projectId
     * @return array
     */
    public static function getlist($source, $projectId)
    {
        $datalist = Upload::find()->where('source=:source AND project_id=:projectId', ['source' => $source, 'projectId' => $projectId])->all();
        return $datalist;
    }

    /**
     * 文件链接
     * @param mixed $source
     * @param mixed $projectId
     * @return string
     */
    public static function getLinkText($source, $projectId)
    {
        $datalist = Upload::find()->where('source=:source AND project_id=:projectId', ['source' => $source, 'projectId' => $projectId])->all();
        $linkText = "";
        foreach ($datalist as $key => $row) {
            $linkText .= $dot . Url::toRoute(['/d/f', 'id' => $row->keyid], true);
            $dot = "；";
        }
        return $linkText;
    }

    /**
     * 获取文件
     * @param mixed $id
     * @return mixed
     */
    public static function getFileById($id)
    {
        $upload = self::getItemById($id);
        if ($upload) {
            self::renderFile($upload);
        } else {
            exit('file not found');
        }
    }

    /**
     * 获取文件
     * @param mixed $id
     * @return mixed
     */
    public static function getFileByUuid($uuid)
    {
        $upload = self::getItemByUuid($uuid);
        if ($upload) {
            self::renderFile($upload);
        } else {
            exit('file not found');
        }
    }

    /**
     * 渲染文件
     * @param mixed $file
     */
    private static function renderFile($file)
    {
        if ($file) {
            $resourcePath = Yii::getAlias('@resource');
            $absolutePath = $resourcePath . '/' . $file->file_name;

            //echo ($absolutePath);

            if (file_exists($absolutePath)) {
                return Yii::$app->response->sendFile($absolutePath, $file->real_name, ['inline' => true])->send();
            }
        }
    }
}
