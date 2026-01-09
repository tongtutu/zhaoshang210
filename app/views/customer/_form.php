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
$isEdit = !$model->isNewRecord;
AppAsset::addScript($this, Yii::$app->params['res.url'] . '/static/plugins/distpicker/distpicker.js');

?>
<!-- Nav tabs -->
<?php $form = ActiveForm::begin(
    [
        'id' => 'customer-form',
        'options' => ['enctype' => 'multipart/form-data'],
    ]
); ?>
<style>
.help-block.error {
    color: red;
    font-size: 14px;
}
</style>
<div class="card">
    <div class="card-header d-flex justify-content-end align-items-center" <?= $isEdit ? 'style="display:none !important;"' : '' ?>>
        <div class="draft-btn-group">
            <!-- 保存到草稿箱按钮 -->
            <?= Html::button('<i class="fa fa-save"></i> 保存到草稿箱', [
                'class' => 'btn btn-secondary btn-sm me-2 draft-save',
                'id' => 'draft-save-btn'
            ]) ?>
            <!-- 获取最后一次草稿按钮 -->
            <?= Html::button('<i class="fa fa-history"></i> 获取最后一次草稿', [
                'class' => 'btn btn-info btn-sm me-2 draft-get',
                'id' => 'draft-get-btn'
            ]) ?>
        </div>
    </div>
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
$draftSaveUrl = Url::to(['customer/save-draft']); // 后端保存草稿接口
$draftGetUrl = Url::to(['customer/get-last-draft']); // 后端获取草稿接口
$this->registerJs(
    <<<JS
$(document).ready(function() {
    // 1. 保存到草稿箱逻辑
    $('#draft-save-btn').click(function() {
        // 禁用按钮防止重复提交
        $(this).attr('disabled', true).text('保存中...');
        
        // 获取表单所有数据
        var formData = $('#customer-form').serialize();
        
        $.ajax({
            url: '{$draftSaveUrl}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.code === 200) {
                    alert('草稿保存成功！');
                } else {
                    alert('草稿保存失败：' + res.msg);
                }
                // 恢复按钮状态
                $('#draft-save-btn').attr('disabled', false).html('<i class="fa fa-save"></i> 保存到草稿箱');
            },
            error: function() {
                alert('网络错误，草稿保存失败！');
                $('#draft-save-btn').attr('disabled', false).html('<i class="fa fa-save"></i> 保存到草稿箱');
            }
        });
    });

    // 2. 获取最后一次草稿逻辑
    $('#draft-get-btn').click(function() {
        $(this).attr('disabled', true).text('加载中...');
        
        $.ajax({
            url: '{$draftGetUrl}',
            type: 'GET',
            data: "",
            dataType: 'json',
            success: function(res) {
                if (res.code === 200 && res.data) {
                    // 填充草稿数据到表单
                    var draft = res.data;
                    
                    if (draft.tags && typeof draft.tags === 'string') {
                        // 步骤1：按制表符/空格分割成单个标签项（兼容\t、空格、多个空格）
                        var tagItems = draft.tags.split(/[\t\s]+/).filter(item => item);
                        // 步骤2：遍历每个标签项，提取ID
                        var tagIds = [];
                        tagItems.forEach(function(item) {
                            // 按逗号分割，取第一个值（ID）并转数字
                            var tagId = item.split(',')[0];
                            if (tagId && !isNaN(tagId)) {
                                tagIds.push(tagId);
                            }
                        });
                        // 替换原tags为纯ID数组
                        draft.tags = tagIds;
                    }
                    for (var key in draft) {
                        if ($('textarea[name="customer[' + key + ']"]').length) {
                            // 文本域填充
                            $('textarea[name="customer[' + key + ']"]').val(draft[key]);
                        } else if (key === 'content') {
                            var ue = UE.getEditor('customer-content');
                            ue.ready(function() {
                                ue.setContent(draft[key] || '', false);
                            });
                            
                        }else if (key === 'tags' && Array.isArray(draft[key])) {
                            // 清空所有标签复选框的勾选状态
                            $('input[name="Customer[tags][]"]').prop('checked', false);
                            // 批量勾选匹配的标签（value为标签ID）
                            draft[key].forEach(function(tagId) {
                                $('input[name="Customer[tags][]"][value="' + tagId + '"]').prop('checked', true);
                            });
                        }else{
                            // 普通输入框/下拉框填充
                            $('#customer-' + key).val(draft[key]);
                            setTimeout(function() {
                                $('#customer-' + key).val(draft[key]);
                                // 触发下拉框change事件，更新联动（如有）
                                $('#customer-' + key).trigger('change');
                            }, 1000);
                        }
                    }
                    if (draft.partner_uid && $('#customer-partner_uid').length) {
                        $('#customer-partner_uid').val(draft.partner_uid);
                        $('#customer-partner_uid').trigger('change');
                    }
                    
                    alert('草稿加载成功！');
                } else {
                    alert('暂无草稿数据');
                }
                $('#draft-get-btn').attr('disabled', false).html('<i class="fa fa-history"></i> 获取最后一次草稿');
            },
            error: function() {
                alert('网络错误，加载草稿失败！');
                $('#draft-get-btn').attr('disabled', false).html('<i class="fa fa-history"></i> 获取最后一次草稿');
            }
        });
    });
});    
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