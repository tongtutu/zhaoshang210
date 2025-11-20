<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use kartik\file\FileInput;
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use kartik\datetime\DateTimePicker;
?>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => true,
    ]
); ?>
<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-6">
                        <?php echo $form->field($model, 'typeid')->dropDownList(ProjectConst::MAINTAIN_TYPE, ['id' => 'typeid']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo $form->field($model, 'content')->textarea(['rows' => 3]); ?>
                    </div>
                </div>
                <div class="row">
                    <div
                        class="<?php if ($source == System::SOURCE_INVEST): ?>col-md-4<?php else: ?>col-md-6<?php endif; ?>">
                        <?php echo $form->field($model, 'steps')->dropDownList(ProjectConst::STEPS, ['id' => 'steps']); ?>
                    </div>
                    <?php if ($source == System::SOURCE_INVEST): ?>
                        <div class="col-md-2">
                            <?php echo $form->field($model, 'bt_demand')->dropDownList(System::STATUS, ['disabled' => true, 'id' => 'bt_demand']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'remind_time')->widget(DateTimePicker::classname(), [
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

                    <div class="col-lg-6">
                        <?php echo $form->field($model, 'prove_file')->hiddenInput(['class' => 'form-control proveFile', 'placeholder' => '证明资料，请上传', 'readonly' => true])->label(false); ?>

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
                new $.Zebra_Dialog(data.message, {
                    modal: false
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
    <div class="card-footer">
        <div class="form-group">
            <?php echo Html::hiddenInput('projectId', $model->id); ?>
            <?php echo Html::hiddenInput('source', $source); ?>
            <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交跟进维护 ', ['class' => 'submitBtn btn  btn-primary']); ?>

        </div>
    </div>

</div> <?php ActiveForm::end(); ?>