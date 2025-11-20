<?php
use yii\helpers\Url;
use yii\bootstrap5\Html;
use kartik\file\FileInput;
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;
?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'workUpload',
        'action' => Url::to(['demand/partner/works-upload']),
    ]
); ?>
<div class="card">
    <div class="card-header">
        提交作品
    </div>
    <div class="card-body">


        <div class="row">
            <div class="col-lg-12">
                <?php echo $form->field($worksModel, 'content')->textarea(); ?>
            </div>
            <div class="col-lg-12">
                <?= FileInput::widget([
                    'name' => 'file',
                    'options' => [
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'browseLabel' => '选择作品',
                        'showUpload' => true,
                        'showCancel' => false,
                        'showRemove' => true,
                        'allowedFileExtensions' => System::ALLOW_UPLOAD_FILE_TYPE,
                        'showPreview' => false,
                        'initialPreview' => '',
                        'initialPreviewAsData' => true,
                        'initialPreviewShowDelete' => true,
                        'overwriteInitial' => true,
                        'previewFileType' => 'any',
                        'uploadAsync' => true,
                        'uploadUrl' => Url::toRoute(['upload/file', 'name' => 'file']),
                        'uploadExtraData' => [
                            Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken(),
                            'source' => System::UPLOAD_SOURCE_WORKS
                        ],
                    ],
                    'pluginEvents' => [
                        'fileuploaderror' => "function(event, data, msg){
                new $.Zebra_Dialog(msg, {
                    modal: false,
                });
            }",
                        'fileuploaded' => new \yii\web\JsExpression("
                            function(event, data, previewId, index) {
                                handleFileUploaded(event, data, previewId, index, '#filelist', '.attachFile');
                            }
                        "),
                    ],
                ]); ?>
                <?php echo $form->field($worksModel, 'attach_file')->hiddenInput(['class' => 'form-control attachFile', 'readonly' => true])->label(false); ?>
            </div>


        </div>

        <div class="row" style="padding-top:10px">
            <div class="col-lg-12">
                <ul id="filelist" class="list-group"></ul>
            </div>
        </div>

    </div>
    <div class="card-footer">
        <?php echo Html::hiddenInput('id', $model->id); ?>
        <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交作品 ', ['class' => 'btn  btn-primary submit']); ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
$(function() {
    // 拦截表单提交事件
    $(document).on('beforeSubmit', '#workUpload', function(event) {
        event.preventDefault(); // 阻止表单的默认提交行为

        var form = $(this);

        // 使用Ajax进行表单提交
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: new FormData(form[0]), // 使用FormData对象传递表单数据
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.state == 'success') {
                    new $.Zebra_Dialog(
                        response.message, {
                            type: "information",
                            modal: false,
                            onClose: function(caption) {
                                window.location.reload();
                            }
                        }
                    );
                } else {
                    new $.Zebra_Dialog(response.message, {
                        modal: false,
                        type: "error",
                    });
                }
            },
            error: function() {
                // 处理错误响应
                new $.Zebra_Dialog('提交失败，请重试', {
                    modal: false,
                });
            }
        });

        return false; // 防止表单的默认提交行为
    });
});
</script>