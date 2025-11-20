<?php
use Yii;
use yii\web\View;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use kartik\file\FileInput;
use bagesoft\functions\UserFunc;
use bagesoft\helpers\Utils;
use kartik\select2\Select2;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\functions\ProjectFunc;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;

$inputAddon = '';
if ($model->isNewRecord) {
    $inputAddon = ' checkValue';
}
AppAsset::addScript($this, Yii::$app->params['res.url'] . '/static/plugins/distpicker/distpicker.js');

?>
<!-- Nav tabs -->
<?php $form = ActiveForm::begin(
    [
        'id' => 'customer-form',

    ]
); ?>
<style>
.help-block.error {
    color: red;
    font-size: 14px;
}
</style>
<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="base">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-lg-3">
                                <?php echo $form->field($model, 'realname')->textInput(['maxlength' => true])->label($model->getAttributeLabel('realname') . ' <span class="required-star">*</span>'); ?>
                            </div>
                            <div class="col-lg-3">
                                <?php echo $form->field($model, 'sex')->dropDownList(System::SEX)->label($model->getAttributeLabel('sex') . ' <span class="required-star">*</span>'); ?>
                            </div>
                            <div class="col-lg-3">
                                <?php echo $form->field($model, 'job_title')->textInput(['maxlength' => true])->label($model->getAttributeLabel('job_title') . ' <span class="required-star">*</span>'); ?>
                            </div>
                            <div class="col-lg-3">
                                <?php echo $form->field($model, 'stars', [])->dropDownList(System::STARS)->label($model->getAttributeLabel('stars') . ' <span class="required-star">*</span>'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <?php echo $form->field($model, 'phone', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'phone']])->textInput(['maxlength' => true])->label($model->getAttributeLabel('phone') . ' <span class="required-star">*</span>'); ?>
                            </div>
                            <div class="col-lg-3">
                                <?php echo $form->field($model, 'phone1', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'phone']])->textInput(['maxlength' => true]); ?>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'project_name')->textInput(['maxlength' => true])->label($model->getAttributeLabel('project_name') . ' <span class="required-star">*</span>'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'company_name')->textInput(['maxlength' => true])->label($model->getAttributeLabel('company_name') . ' <span class="required-star">*</span>'); ?>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'usci_code', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'usciCode']])->textInput(['maxlength' => true])->label($model->getAttributeLabel('usci_code') . ' <span class="required-star">*</span>'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 ">
                                <div class="row" data-toggle="distpicker">
                                    <div class="col-lg-4">
                                        <?php echo $form->field($model, 'province', ['inputOptions' => ['data-province' => $model->province]])->dropDownList([])->label($model->getAttributeLabel('province') . ' <span class="required-star">*</span>'); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $form->field($model, 'city', ['inputOptions' => ['data-city' => $model->city]])->dropDownList([])->label($model->getAttributeLabel('city') . ' <span class="required-star">*</span>'); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $form->field($model, 'area', ['inputOptions' => ['data-district' => $model->area]])->dropDownList([]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'address', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'address']])->textInput(['maxlength' => true])->label($model->getAttributeLabel('address') . ' <span class="required-star">*</span>'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'partner_uid')->widget(Select2::className(), [
                                    'data' => UserFunc::getPartners($this->context->user->id),
                                    'options' => ['placeholder' => '选择伙伴', 'disabled' => $partnerDisabled],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label($model->getAttributeLabel('partner_uid') . ' <span class="required-star">*</span>'); ?>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'expand_type', [])->dropDownList(ProjectConst::EXPAND_TYPE); ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">

                                <?php echo $form->field($model, 'content', [])->widget('bagesoft\widget\ueditor\Ueditor', ['route' => 'uploader/ueditor'])->label($model->getAttributeLabel('content') . ' <span class="required-star">*</span>'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <?php echo $form->field($model, 'attach_file')->hiddenInput(['class' => 'form-control attachFile', 'readonly' => true])->label($model->getAttributeLabel('attach_file') . ' <span class="required-star">*</span>'); ?>
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
                                            'source' => System::UPLOAD_SOURCE_CUSTOMER,
                                        ],
                                    ],
                                    'pluginEvents' => [
                                        'fileuploaderror' => "function(event, data, msg){
                                            new $.Zebra_Dialog(msg, {
                                                modal: false,
                                            });
                                        }",
                                        'fileuploaded' =>  new \yii\web\JsExpression("
                                            function(event, data, previewId, index) {
                                                handleFileUploaded(event, data, previewId, index, '#filelist', '.attachFile');
                                            }
                                        "),
                                    ],
                                ]); ?>


                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <a href="<?php echo Yii::$app->params['res.url']; ?>/static/docs/20250120项目调研评估报告-市场信息发布用.docx"
                                        class="class" target="_blank"><i class="fa fa-paperclip"></i> 报告模版下载</a>
                                </div>
                            </div>
                        </div>
                        <?php if(!$model->isNewRecord):?>
                        <div class="row" style="padding-top:10px">
                            <?php $attachFiles = UploadFunc::getlist(System::UPLOAD_SOURCE_CUSTOMER, $model->id); ?>
                            <div class="col-lg-12">
                                <ul id="filelist" class="list-group">
                                    <?php foreach ($attachFiles as $key => $file): ?>
                                    <li class="list-group-item d-flex align-items-center"
                                        id="file-<?php echo $file->id?>"><span class="file-name">&nbsp;&nbsp;<a
                                                href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                target="_blank"><i class="fa fa-paperclip"></i>
                                                <?php echo Html::encode($file->real_name); ?>
                                            </a></span>&nbsp;&nbsp;<button type="button"
                                            class="btn btn-danger btn-xs delete-file"
                                            data-file-id="<?php echo $file->id?>" data-file-list="#filelist"
                                            data-attach-file=".attachFile"><i class="fa fa-trash"></i> 删除</button></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif?>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-success card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">项目标签 <span class="required-star">*</span></h3>

                                    </div>
                                    <!-- <div class="card-body no-pb "> -->
                                    <div class="card-body no-pb " style="height: 700px;overflow: auto;">

                                        <?php if ($model->getFirstError('tags')): ?>
                                            <div class="alert alert-warning alert-dismissible">
                                            <?php echo  $model->getFirstError('tags') ?> </div>
                                        <?php endif; ?>

                                        <?php foreach (ProjectFunc::listTags() as $key => $row): ?>
                                        <div class="callout">
                                            <strong><?php echo $row['name']; ?></strong>
                                            <div class="row">
                                                <?php foreach ($row['list'] as $sub): ?>
                                                <div class="col-lg-6">
                                                    <label style=" font-weight:normal"> <input name="Customer[tags][]"
                                                            type="checkbox" value="<?php echo $sub['id']; ?>"
                                                            <?php if (in_array($sub['id'], $model->tags)): ?>checked="checked"
                                                            <?php endif; ?> />
                                                        <small><?php echo $sub['tag_name']; ?></small> </label>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
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
<?php



?>
<?php ActiveForm::end(); ?>

<?php
$checkUrl = Url::toRoute(["value-check"]);
$this->registerJs(
    <<<JS
$("#distpicker").distpicker();
$('.checkValue').on('blur', function() {
    var inputValue = $(this).val();
    var fieldName = $(this).data('field');
    if (inputValue.trim() !== '') {
        $.ajax({
            url: '{$checkUrl}', // 替换为你的后端 URL
            type: 'GET',
            data: {inputValue: inputValue, fieldName: fieldName},
            success: function(response) {
                // 在这里处理返回的数据 response
                if(response.state == 'error'){
                    new $.Zebra_Dialog(response.message, {
                        modal: false,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
});


JS
    ,
    View::POS_END
);

?>