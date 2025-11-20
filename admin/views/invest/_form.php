<?php
use Yii;
use yii\web\View;
use yii\helpers\Url;
use yii\bootstrap5\Html;
use admin\assets\AppAsset;
use kartik\file\FileInput;
use bagesoft\functions\TagsFunc;
use bagesoft\functions\UserFunc;
use kartik\select2\Select2;
use bagesoft\constant\System;
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
        'options' => ['enctype' => 'multipart/form-data'],
    ]
); ?>

<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="base">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo $form->field($model, 'project_name')->textInput(['maxlength' => true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'channel_id')->dropDownList(ProjectConst::CHANNEL); ?>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'channel_name', ['inputOptions' => ['class' => 'form-control ', 'placeholder' => '渠道是中介请填写名称，自拓无需填写']])->textInput(['maxlength' => true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'contact_name', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'contactName']])->textInput(['maxlength' => true]); ?>
                            </div>

                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'contact_phone', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'phone']])->textInput(['maxlength' => true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'company_name')->textInput(['maxlength' => true]); ?>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'usci_code', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'usciCode']])->textInput(['maxlength' => true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 ">
                                <div class="row" data-toggle="distpicker">
                                    <div class="col-lg-4">
                                        <?php echo $form->field($model, 'province', ['inputOptions' => ['data-province' => $model->province]])->dropDownList([]); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $form->field($model, 'city', ['inputOptions' => ['data-city' => $model->city]])->dropDownList([]); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $form->field($model, 'area', ['inputOptions' => ['data-district' => $model->area]])->dropDownList([]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'address', ['inputOptions' => ['class' => 'form-control ' . $inputAddon, 'data-field' => 'address']])->textInput(['maxlength' => true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'project_assess')->dropDownList(['0' => '-'] + TagsFunc::getTagsList(ProjectConst::PROJECT_ASSESS_TAG_ID))->label($model->getAttributeLabel('project_assess')); ?>
                            </div>
                        </div>
                        <?php if ($hasManager == 1): ?>
                        <div class="row">
                            <div class="col-lg-6">

                                <?php echo $form->field($model, 'manager_uid')->widget(Select2::className(), [
                                        'data' => UserFunc::getManagers($this->context->user->id),
                                        'options' => ['placeholder' => '选择伙伴', 'disabled' => !$model->isNewRecord],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ]); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo $form->field($model, 'content', [])->widget('bagesoft\widget\ueditor\Ueditor', ['route' => 'uploader/ueditor']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo $form->field($model, 'attach_file')->hiddenInput(['class' => 'form-control attachFile', 'readonly' => true]); ?>
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
                                            'source' => System::UPLOAD_SOURCE_INVEST,
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
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-success card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">项目标签</h3>
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
                                                    <label style=" font-weight:normal"> <input name="Invest[tags][]"
                                                            type="checkbox" value="<?php echo $sub['id']; ?>"
                                                            <?php if (in_array($sub['id'], $model->tags)): ?>checked="checked"
                                                            <?php endif; ?> />
                                                        <small><?php echo $sub['tag_name']; ?> </small></label>
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