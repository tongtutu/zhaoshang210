<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
use kartik\file\FileInput;
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use kartik\datetime\DateTimePicker;
?>
<style>
    /* 强制遮罩层穿透容器 */
    .ZebraDialogOverlay {
        position: fixed !important;
        /* 关键属性 */
        z-index: 9998 !important;
        /* 需高于AdminLTE侧边栏(通常1030) */
        width: 100vw !important;
        /* 视口全宽 */
        height: 100vh !important;
        /* 视口全高 */
        left: 0 !important;
        top: 0 !important;
    }

    /* 调整对话框本体 */
    .ZebraDialog {
        z-index: 9999 !important;
        position: fixed !important;
        transform: translate(-50%, -50%);
        /* 居中修正 */
    }
</style>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['maintain/validate']),
    ]
); ?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">跟进维护发布</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <?php echo $form->field($maintain, 'typeid')->dropDownList(ProjectConst::MAINTAIN_TYPE, ['id' => 'typeid'])->label($maintain->getAttributeLabel('typeid') . ' <span class="required-star">*</span>'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo $form->field($maintain, 'content')->textarea(['rows' => 3])->label($maintain->getAttributeLabel('content') . ' <span class="required-star">*</span>'); ?>
                    </div>
                </div>
                <div class="row">
                    <div
                        class="<?php if ($source == System::SOURCE_CUSTOMER): ?>col-md-4<?php else: ?>col-md-6<?php endif; ?>">
                        <?php echo $form->field($maintain, 'steps')->dropDownList(ProjectConst::STEPS, ['id' => 'steps'])->label($maintain->getAttributeLabel('steps') . ' <span class="required-star">*</span>'); ?>
                    </div>
                    <?php if ($source == System::SOURCE_CUSTOMER): ?>
                        <div class="col-md-2">
                            <?php echo $form->field($maintain, 'bt_demand')->dropDownList(System::STATUS, ['disabled' => true, 'id' => 'bt_demand']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <?php echo $form->field($maintain, 'remind_time')->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => '无需提醒请不要选择'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:00', // 仅显示日期和小时
                                'todayHighlight' => true,
                                'minView' => 'day', // 仅显示日期和小时
                                'startDate' => date('Y-m-d H:00', time() + 3600 * 24), // 开始日期
                                'endDate' => date('Y-m-d H:00', time() + 3600 * 24 * 30 * 6),
                                'todayBtn' => true, // 显示“今天”按钮
                        
                            ],
                        ]); ?>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-8">
                        <?php echo $form->field($maintain, 'prove_file')->hiddenInput(['class' => 'form-control proveFile', 'placeholder' => '证明资料，请上传', 'readonly' => true])->label($maintain->getAttributeLabel('prove_file') . ' <span class="required-star">*</span>'); ?>

                        <?= FileInput::widget([
                            'name' => 'file',
                            'options' => [
                                'multiple' => true,
                            ],
                            'pluginOptions' => [
                                'browseLabel' => '选择证明资料',
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
                                    'source' => System::UPLOAD_SOURCE_MAINTAIN
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
                                        handleFileUploaded(event, data, previewId, index, '#filelist', '.proveFile');
                                    }
                                "),
                            ],
                        ]); ?>

                    </div>
                </div>
                <div class="row" style="padding-top:10px">
                    <div class="col-lg-12">
                        <ul id="filelist" class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <?php echo Html::hiddenInput('projectId', $model->id); ?>
        <?php echo Html::hiddenInput('source', $source); ?>
        <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交跟进维护 ', ['class' => 'submitBtn btn  btn-primary']); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<script>
    $('.submitBtn').click(function () {
        $.ajax({
            url: "<?php echo Url::toRoute(['maintain/save']); ?>",
            type: "POST",
            dataType: "json",
            data: $('form').serialize(),
            success: function (data) {
                new $.Zebra_Dialog(data.message, {
                    modal: false
                });
                if (data.state == 'success') {
                    window.location.reload();
                }
            },
            error: function () {
                new $.Zebra_Dialog('网络错误！', {
                    modal: false
                });
            }
        });
        return false;
    });
</script>
<?php
$currentSteps = $model->steps;
$script = <<<JS
$(document).ready(function() {
    $("#steps").val('{$currentSteps}');
    // 监听状态字段值的变化
    $('#steps').on('change', function() {
        if ($(this).val() === '4') {
            $('#bt_demand').prop('disabled', false);
        } else {
            $('#bt_demand').prop('disabled', true).val('2');
        }
    });

    // 页面加载时调用一次以确保初始状态正确
    $('#steps').change();

});
JS;

$this->registerJs($script);
?>