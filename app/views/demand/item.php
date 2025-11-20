<?php
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\bootstrap5\Modal;
use kartik\file\FileInput;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use kartik\datetime\DateTimePicker;

$this->title = '需求管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
AppAsset::addScript($this, Yii::$app->params['res.url'] . '/static/plugins/zebra_dialog/zebra_dialog.min.js');
AppAsset::addCss($this, Yii::$app->params['res.url'] . '/static/plugins/zebra_dialog/css/materialize/zebra_dialog.min.css');

?>
<div class="row">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">需求基本信息</h1>
            </div>
            <div class="card-body">
                <div class="col-12 table-responsive">
                    <table class="table table-">
                        <tbody>
                            <tr>
                                <th style="width:20%">需求编号</th>
                                <td><?php echo $model->id; ?> </small>
                                </td>
                            </tr>
                            <tr>
                                <th>发布时间</th>
                                <td><?php echo date('Y-m-d H:i:s', $model->created_at); ?></td>
                            </tr>
                            <tr>
                                <th>项目所在地</th>
                                <td><?php echo Html::encode($model->project_location); ?></td>
                            </tr>

                            <tr>
                                <th>期望首次提交时间</th>
                                <td><?php echo Html::encode($model->hope_time); ?></td>
                            </tr>
                            <tr>
                                <th>状态</th>
                                <td><?php if ($model->produce_num > 0 && $model->produce_num <= ProjectConst::DEMAND_STATUS_N): ?><?php echo ProjectConst::DEMAND_WORKS_UPLOAD[$model->produce_num]; ?>,<?php endif ?><?php echo ProjectConst::DEMAND_STATUS[$model->state]; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>附件</th>
                                <td>
                                    <div class="attachFile">
                                        <ul>
                                            <?php foreach ($attachFiles as $key => $file): ?>
                                            <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                    target="_blank"><?php echo Html::encode($file->real_name); ?> </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>项目介绍</th>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $model->content; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4">
        <?php echo $this->render($model->source == System::SOURCE_CUSTOMER ? '/_include/project/customer' : '/_include/project/invest', [
            'project' => $project,

        ]); ?>
    </div>

</div>
<div class="row">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">作品提交记录</h1>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if ($worksList): ?>

                    <?php foreach ($worksList as $key => $row): ?>
                    <?php $attachFiles = UploadFunc::getlist(System::UPLOAD_SOURCE_WORKS, $row->id); ?>
                    <?php $auditFiles = UploadFunc::getlist(System::UPLOAD_SOURCE_WORKS_AUDIT, $row->id); ?>
                    <!-- timeline time label -->
                    <div class="time-label">
                        <span class="bg-primary"><?php echo date('Y-m-d H:i:s', $row->created_at); ?></span>
                    </div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <div>
                        <i class="fas fa-user bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i>
                                <?php echo ProjectConst::DEMAND_WORKS_AUDIT[$row->state]; ?></span>
                            <h3 class="timeline-header"><span
                                    class="text-primary"><?php echo Html::encode($row->worker_name) ?></span>
                                <?php echo ProjectConst::DEMAND_WORKS_UPLOAD[$row->produce_num]; ?></h3>

                            <div class="timeline-body">
                                <div>
                                    <?php echo nl2br(Html::encode($row->content)); ?>
                                </div>


                            </div>
                            <div class="timeline-footer">
                                <div class="attachFile">
                                    <ul>
                                        <?php foreach ($attachFiles as $key => $file): ?>
                                        <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                target="_blank"><i class="fa fa-paperclip"></i>
                                                <?php echo Html::encode($file->real_name); ?> </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php if (
                                            $row->state == ProjectConst::DEMAND_WORKS_AUDIT_WAIT
                                            && $model->state != ProjectConst::DEMAND_STATUS_SUCCESS
                                            && ($model->uid == $this->context->user->id || $model->partner_uid == $this->context->user->id)
                                        ): ?>
                                <a class="btn btn-warning btn-sm open-modal"
                                    data-worksid="<?php echo $row->id; ?>">提交审核意见</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- END timeline item -->
                    <?php if ($row->state != ProjectConst::DEMAND_WORKS_AUDIT_WAIT): ?>
                    <div>
                        <i class="fas fa-comments bg-yellow"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i>
                                <?php echo date('Y-m-d H:i:s', $row->audit_at); ?></span>
                            <h3 class="timeline-header">作品审核：<span
                                    class="text-primary"><?php echo Html::encode($row->audit_name) ?></span></h3>
                            <?php if ($row->reply_content): ?>
                            <div class="timeline-body">
                                <?php echo nl2br(Html::encode($row->reply_content)); ?>
                            </div>
                            <?php endif ?>
                            <?php if ($auditFiles): ?>
                            <div class="timeline-footer">
                                <div class="attachFile">
                                    <ul>
                                        <?php foreach ($auditFiles as $key => $file): ?>
                                        <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                target="_blank"><i class="fa fa-paperclip"></i>
                                                <?php echo Html::encode($file->real_name); ?> </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php endforeach; ?>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if (
            $model->worker_uid == $this->context->user->id
            && $model->worker_accept == ProjectConst::WORKS_ACCEPT_APPROVE
            && $model->state != ProjectConst::DEMAND_STATUS_SUCCESS
            && $getWaitWorks <= 0
            && $model->deleted <= System::DELETE_LEVEL_1
        ): ?>

        <?php echo $this->render('_worksupload', [
                'worksModel' => $worksModel,
                'model' => $model,
                'getWaitWorks' => $getWaitWorks,

            ]); ?>
        <?php endif; ?>
    </div>
</div>

<?php
// 模态框
Modal::begin([
    'title' => '创作审核',
    'id' => 'demandWorksModal',
    'size' => 'modal-lg', // 设置模态框大小为大尺寸
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
]);

$form = ActiveForm::begin([
    'id' => 'demandWorksForm',
    'action' => ['demand/works-audit-save'],
]);
?>


<div class="card card-primary ">

    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->field($worksModel, 'state')->dropDownList(ProjectConst::DEMAND_WORKS_AUDIT_SELECT, ['id' => 'demandState']); ?>
            </div>
            <div class="col-lg-6">
                <?php echo $form->field($worksModel, 'hope_time')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => '', 'id' => 'demandHopeTime'],
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
            <div class="col-lg-12">
                <?php echo $form->field($worksModel, 'reply_content')->textarea(['rows' => 3]); ?>
            </div>
            <div class="col-lg-12">
                <?php echo $form->field($worksModel, 'audit_file')->hiddenInput(['class' => 'form-control auditFile', 'id' => 'auditFile', 'placeholder' => '', 'readonly' => true])->label(); ?><?= FileInput::widget([
                                   'name' => 'file',
                                   'options' => [
                                       'multiple' => true,
                                   ],
                                   'pluginOptions' => [
                                       'browseLabel' => '附件上传',
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
                                           'source' => System::UPLOAD_SOURCE_WORKS_AUDIT
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
                                                handleFileUploaded(event, data, previewId, index, '#filelist', '.auditFile');
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
    <div class="card-footer">
        <?php echo Html::hiddenInput('worksId', '', ['id' => 'worksId']); ?>
        <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交审核结果 ', ['class' => 'submitBtn btn  btn-primary']); ?>
    </div>
</div>

<?php
ActiveForm::end();
Modal::end();
$worksAcceptReject = ProjectConst::WORKS_ACCEPT_REJECT;
$script = <<<JS
$(document).ready(function() {
    $('.open-modal').click(function() {
        var worksid = $(this).data('worksid');
        $('#worksId').val(worksid);
        $('#demandWorksForm')[0].reset(); // 重置表单内容
        $("#auditFile").val('');
        $('#demandWorksModal').modal('show');
    });

    $('#demandWorksForm').on('beforeSubmit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            data: $(this).serialize(),
            success: function(response) {
                
                // 在这里处理成功提交后的逻辑
                if (response.state == 'success') {
                    $('#demandWorksModal').modal('hide');
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
            error: function(xhr, status, error) {
                new $.Zebra_Dialog('提交失败，请重试', {
                    modal: false,
                });
                return false;
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });

    // 监听状态字段值的变化
    $('#demandState').on('change', function() {
        if ($(this).val() === '{$worksAcceptReject}') {
            $('#demandHopeTime').prop('disabled', false);
        } else {
            $('#demandHopeTime').prop('disabled', true);
        }
    });

    // 页面加载时调用一次以确保初始状态正确
    $('#demandState').change();
});
JS;

$this->registerJs($script);
?>