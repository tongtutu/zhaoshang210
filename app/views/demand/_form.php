<?php
use bagesoft\constant\ProjectConst;
use bagesoft\constant\System;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
    ]
); ?>
<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">

                        <div class="col-lg-6">
                            <?php echo $form->field($model, 'project_location')->textInput(['maxlength' => true]); ?>
                        </div>
                        <div class="col-lg-6">
                            <?php echo $form->field($model, 'hope_time')->widget(DateTimePicker::classname(), [
                                'options' => ['placeholder' => ''],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd hh:00', // 仅显示日期和小时
                                    'todayHighlight' => true,
                                    'minView' => 'day', // 仅显示日期和小时
                                    'startDate' => date('Y-m-d H:00', time() + 3600 * 24), // 开始日期
                                    'endDate' => date('Y-m-d H:00', time() + 3600 * 24 * 30 * 6),
                                ],
                            ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">

                            <?php echo $form->field($model, 'content', [])->widget('bagesoft\widget\ueditor\Ueditor', ['route' => 'uploader/ueditor']); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo $form->field($model, 'attach_file', [])->hiddenInput(['class' => 'form-control attachFile', 'placeholder' => '', 'readonly' => true]); ?>
                        </div>
                        <div class="col-lg-12">
                            <?= FileInput::widget([
                                'name' => 'file',
                                'options' => [
                                    'multiple' => true,
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
                                    'uploadUrl' => Url::toRoute(['upload/file', 'name' => 'file']),
                                    'uploadExtraData' => [
                                        Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken(),
                                        'source' => System::UPLOAD_SOURCE_DEMAND,
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
                        </div>

                    </div>
                    <div class="row" style="padding-top:10px">
                        <div class="col-lg-12">
                            <ul id="filelist" class="list-group"></ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">

                    <?php echo $this->render($source == System::SOURCE_CUSTOMER ? '/_include/project/customer' : '/_include/project/invest', [
                        'project' => $project,

                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <div>
                <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-send-o"></i>录入 ' : '编辑', ['class' => 'btn btn-primary']); ?>
                <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']); ?> <a
                    href="<?php echo Url::to(['index']); ?>" class="btn"><i class="fa fa-history"></i>返回</a>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>