<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\widgets\LinkPager;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use kartik\daterange\DateRangePicker;

$this->title = '跟进维护';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '列表';
$request = Yii::$app->request;

$defaultStartDate = date('Y-m-d', strtotime('-7 days'));
$defaultEndDate = date('Y-m-d');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目名称</span>
                                </div>
                                <?php echo Html::textInput('projectName', $request->get('projectName'), ['class' => 'form-control form-control-sm ', 'placeholder' => '', 'id' => 'projectName']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">跟进人</span>
                                </div>
                                <?php echo Html::textInput('parterName', $request->get('parterName'), ['class' => 'form-control form-control-sm ', 'placeholder' => '', 'id' => 'parterName']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">跟进类型</span>
                                </div>
                                <?php echo Html::dropDownList('typeid', $request->get('typeid'), ProjectConst::MAINTAIN_TYPE, ['class' => 'form-control  form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">审核结果</span>
                                </div>
                                <?php echo Html::dropDownList('state', $request->get('state'), ProjectConst::MAINTAIN_STATUS, ['class' => 'form-control  form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">提交时间</span>
                                </div> <?php
                                echo DateRangePicker::widget([
                                    'name' => 'date',
                                    'convertFormat' => true,
                                    'value' => $request->get('date'),
                                    'pluginOptions' => [
                                        'timePicker' => false,
                                        'timePickerIncrement' => 15,
                                        'locale' => [
                                            'format' => 'Y-m-d'
                                        ],
                                        'maxDate' => date('Y-m-d', strtotime('+1 days')),
                                        'autoApply' => true,          // 选择日期后自动应用
                                        'autoUpdateInput' => true,    // 自动更新输入框的值
                                    ],
                                    'options' => [
                                        'class' => 'form-control form-control-sm custom-select',
                                        'readonly' => true,
                                    ]
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-3"> <?php echo Html::hiddenInput('opt', 'search'); ?>
                            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i>搜索</button>
                            <a href="<?php echo Url::to(['index']); ?>" class="btn btn-xs btn-link"> <i
                                    class="fa fa-undo"></i>取消</a>
                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card ">
            <?php if ($count > 0): ?>
                <div class="card-header">

                    <div class="card-title"> <a class="btn btn-default" href="<?php echo Url::to(array_merge(['export-download'], Yii::$app->request->get())); ?>">
                            <i class="fa fa-file-o"></i> 导出 </a></div>
                    <div class="card-tools">
                        共 <kbd> <?php echo $count; ?></kbd> 条记录
                    </div>

                </div>
            <?php endif; ?>
            <div class="card-body p-0">
                <div class="row">

                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages"
                                style="overflow-x: auto; min-width: 1px; min-height:20vh">
                                <table class="table table-hover" style="width: max-content; min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>项目名称</th>
                                            <th>创建人</th>
                                            <th>跟进人</th>
                                            <th>跟进类型</th>
                                            <th>项目阶段</th>
                                            <th>状态</th>
                                            <th>下次跟进</th>
                                            <th>附件</th>
                                            <th>提交时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <?php $attachFiles = UploadFunc::getlist(System::UPLOAD_SOURCE_MAINTAIN, $row['id']); ?>
                                                <tr>
                                                    <td> <?php echo $row['id']; ?> </td>
                                                    <td><kbd><?php echo System::SOURCE[$row['source']]; ?></kbd>
                                                        <a href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>">
                                                            <?php echo Html::encode($row['project_name']); ?></a>
                                                    </td>
                                                    <td> <?php echo Html::encode($row['partner_name']); ?> </td>
                                                    <td> <?php echo Html::encode($row['username']); ?> </td>
                                                    <td><?php echo ProjectConst::MAINTAIN_TYPE[$row['typeid']]; ?> </td>
                                                    <td><?php echo ProjectConst::STEPS[$row['steps']]; ?> </td>
                                                    <td><?php echo ProjectConst::MAINTAIN_STATUS[$row['state']]; ?></td>
                                                    <td><?php echo Html::encode($row['remind_time']) ?></td>
                                                    <td>
                                                        <?php if ($attachFiles): ?>
                                                            <div class="btn-group "> <a type="button"
                                                                    class="btn btn-default btn-sm">附件=></a>
                                                                <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"> <span class="caret"></span></a>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <?php foreach ($attachFiles as $key => $file): ?>
                                                                        <li><a class="dropdown-item"
                                                                                href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                                                target="_blank"><i class="fa fa-paperclip"></i>
                                                                                <?php echo Html::encode($file->real_name); ?>

                                                                            </a>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date('Y-m-d H:i', $row['created_at']); ?></td>
                                                    <td>

                                                        <div class="btn-group "> <a type="button" class="btn btn-default btn-sm"
                                                                href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>">详情</a>
                                                            <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false"> <span class="caret"></span></a>
                                                            <ul class="dropdown-menu dropdown-menu-right">



                                                                <?php if (
                                                                    $row['uid'] == $this->context->user->id
                                                                    && ($row['state'] == ProjectConst::MAINTAIN_STATUS_WAIT || $row['source'] == System::SOURCE_INVEST)
                                                                ):
                                                                    ?>
                                                                    <!-- 发布者 -->
                                                                    <li><a class="dropdown-item"
                                                                            href="<?php echo Url::to(['update', 'id' => $row['id']]); ?>"
                                                                            role="button">编辑</a></li>
                                                                    <li><a class="dropdown-item bg-danger ajaxLink"
                                                                            data-url="<?php echo Url::to(['delete', 'id' => $row['id']]); ?>"
                                                                            role="button" data-message="确定删除吗？">删除</a></li>
                                                                <?php endif; ?>

                                                                <?php if ($row['state'] == ProjectConst::MAINTAIN_STATUS_WAIT && $row['partner_uid'] == $this->context->user->id): ?>
                                                                    <!-- 伙伴 -->
                                                                    <li><a class="dropdown-item open-modal"
                                                                            data-maintain-id="<?php echo $row['id']; ?>"
                                                                            role="button">审核</a></li>
                                                                <?php endif; ?>

                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php $opt = ' style="display:none"'; ?>
                                            <td colspan="9" class="text-center text-primary">暂无记录</td>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer p-0">
                <div class="mailbox-controls">
                    <div class="float-right">
                        <?php echo LinkPager::widget(
                            [
                                'pagination' => $pagination,
                                'options' => ['class' => 'pagination'],
                                'linkOptions' => ['class' => 'page-link'],
                                'activePageCssClass' => ' page-item',
                                'disabledPageCssClass' => ' active',
                                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                                'disableCurrentPageButton' => true,
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php
// 模态框
Modal::begin([
    'title' => '跟进维护审核',
    'id' => 'maintainModal',
    'size' => 'modal-lg', // 设置模态框大小为大尺寸
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
]);

$form = ActiveForm::begin([
    'id' => 'maintainForm',
    'action' => ['maintain/partner/audit'],
]);
?>

<div class="card card-primary ">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->field($maintain, 'state')->dropDownList(ProjectConst::MAINTAIN_CHANGE_STATUS)->label('审核结果'); ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?php echo Html::hiddenInput('maintainId', '', ['id' => 'maintainId']); ?>
        <?php echo Html::submitButton('提交 ', ['class' => 'submitBtn btn  btn-primary']); ?>
    </div>
</div>

<?php
ActiveForm::end();
Modal::end();
$script = <<<JS
$(document).ready(function() {
    $('.open-modal').click(function() {
        var maintainId = $(this).data('maintain-id');
        $('#maintainId').val(maintainId);
        $('#maintainForm')[0].reset(); // 重置表单内容
        $('#maintainModal').modal('show');
    });

    $('#maintainForm').on('beforeSubmit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            data: $(this).serialize(),
            success: function(data) {
                new $.Zebra_Dialog(data.message, {
                    modal: false
                });
                // 在这里处理成功提交后的逻辑
                if(data.state=='success'){
                    $('#maintainModal').modal('hide');
                    window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                new $.Zebra_Dialog('提交失败，请重试', {
                    modal: false
                });
                return false;
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });

  
});
JS;

$this->registerJs($script);
?>