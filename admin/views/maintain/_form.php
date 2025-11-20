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
<div class="row">
    <div class="col-lg-12">

        <?php $form = ActiveForm::begin(
            [
                'options' => ['enctype' => 'multipart/form-data'],
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['maintain/validate']),
            ]
        ); ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $form->field($model, 'content')->textarea(['rows' => 6]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php echo $form->field($model, 'steps')->dropDownList(ProjectConst::STEPS); ?>
            </div>
            <div class="col-md-6">

                <?php echo $form->field($model, 'remind_at')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => '无需提醒请不要选择'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ],
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->field($model, 'prove_file')->textInput(['class' => 'form-control proveFile', 'placeholder' => '证明资料，请上传', 'readonly' => true])->label(false); ?>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <?= FileInput::widget([
                        'name' => 'prove',
                        'options' => [
                            'multiple' => false,
                        ],
                        'pluginOptions' => [
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
                            'uploadUrl' => Url::toRoute(['upload/file', 'name' => 'prove']),
                            'uploadExtraData' => [
                                Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken(),
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
        </div>
        <div class="row" style="padding-top:10px">
            <div class="col-lg-12">
                <ul id="filelist" class="list-group"></ul>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <div>
                        <?php echo Html::hiddenInput('projectId', $model->id); ?>
                        <?php echo Html::hiddenInput('source', $source); ?>
                        <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交跟进维护 ', ['class' => 'btn  btn-primary']); ?>
                    </div>
                </div>
            </div>
            <hr>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>