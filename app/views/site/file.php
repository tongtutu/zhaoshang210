<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\file\FileInput;
use bagesoft\constant\System;

$this->title = 'FileInput Example';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title); ?></title>
    <?php
    echo FileInput::widget([
        'name' => 'file',
        'options' => [
            'multiple' => true,
        ],
        'pluginOptions' => [
            // 异步上传需要携带的其他参数，比如商品id等,可选
            'uploadExtraData' => [
                'upload_no' => $uploadNo,
            ],
            // 需要预览的文件格式
            'previewFileType' => 'image',
            'showUploadStats' => false,
            //是否显示文件名
            'showCaption' => true,
            // 预览的文件
            'initialPreview' => isset($p1) ? $p1 : '',
            // 需要展示的图片设置，比如图片的宽度等
            'initialPreviewConfig' => isset($p2) ? $p2 : '',
            // 是否展示预览图
            'initialPreviewAsData' => true,
            // 最少上传的文件个数限制
            'minFileCount' => 1,
            // 最多上传的文件个数限制,需要配置`'multiple'=>true`才生效
            'maxFileCount' => 10,
            // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
            'showRemove' => true,
            // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
            'showUpload' => true,
            //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
            'showBrowse' => true,
            // 展示图片区域是否可点击选择多文件
            'browseOnZoneClick' => true,
            // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
            'fileActionSettings' => [
                // 设置具体图片的查看属性为false,默认为true
                'showZoom' => true,
                // 设置具体图片的上传属性为true,默认为true
                'showUpload' => false,
                // 设置具体图片的移除属性为true,默认为true
                'showRemove' => true,
            ],
            'allowedFileExtensions' => System::ALLOW_UPLOAD_FILE_TYPE,
            'showPreview' => false,
            'maxFileSize' => 10000,
            'initialPreviewShowDelete' => true,

            'overwriteInitial' => true,
            'uploadAsync' => true,
            'uploadUrl' => Url::toRoute(['upload']),
            'showCancel' => false
        ],
        'pluginEvents' => [
            // 'filebatchselected' => "function (event, files){
            //         $(this).fileinput('upload');
            //     }",
            'fileuploaderror' => "function(event, data, msg){
                alert(msg);
            }",
            'fileuploaded' => "function(event, data, index, fileId) {
            //console.log(data.response.filename);
             alert(data.response.filename);

     } ",
        ],
    ]);
    ?>
</head>

<body>
    <h1><?= Html::encode($this->title); ?></h1>
    <div>
        <!-- FileInput widget will be rendered here -->
    </div>
</body>

</html>