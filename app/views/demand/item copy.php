<?php

use bagesoft\constant\ProjectConst;
use bagesoft\constant\System;
use bagesoft\func\AttachFunc;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '需求管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
?>
<div class="row">
    <div class="col-lg-8">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">需求基本信息</h1>
                    </div>
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
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
                                        <th>提交形式</th>
                                        <td><?php echo Html::encode($model->file_type); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>期望首次提交时间</th>
                                        <td><?php echo Html::encode($model->hope_time); ?></td>
                                    </tr>
                                    <tr>
                                        <th>状态</th>
                                        <td><?php echo ProjectConst::DEMAND_STATUS[$model->state]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>附件</th>
                                        <td><?php foreach (AttachFunc::listfile($model->attach_file) as $key => $file): ?>
                                                <a href="<?php echo Yii::$app->params['res.url']; ?>/<?php echo Html::encode($file); ?>"
                                                    class="link-black text-sm" target="_blank"><i
                                                        class="fas fa-link mr-1"></i>附件 </a>
                                            <?php endforeach; ?>
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
            <?php if (
                $model->partner_uid == $this->context->user->id
                && $model->partner_accept == ProjectConst::WORKS_ACCEPT_APPROVE
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">作品提交记录</h1>
                    </div>
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>交付时间</th>
                                        <th>附件</th>
                                        <th>创作次数</th>
                                        <th>状态</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($worksList): ?>
                                        <?php foreach ($worksList as $key => $row): ?>
                                            <tr>
                                                <th><?php echo $row->id; ?></th>
                                                <td><?php echo date('Y-m-d H:i:s', $row->created_at); ?></td>
                                                <td>
                                                    <?php foreach (AttachFunc::listfile($row->attach_file) as $key => $file): ?>
                                                        <a href="<?php echo Yii::$app->params['res.url']; ?>/<?php echo Html::encode($file); ?>"
                                                            class="link-black text-sm" target="_blank"><i
                                                                class="fas fa-link mr-1"></i>附件 </a>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td><?php echo ProjectConst::DEMAND_WORKS_UPLOAD[$row->produce_num]; ?></td>
                                                <td><?php echo ProjectConst::DEMAND_WORKS_AUDIT[$row->state]; ?></td>
                                                <td>
                                                    <?php if ($model->uid == $this->context->user->id && $row->state == ProjectConst::DEMAND_WORKS_AUDIT_WAIT && $model->deleted <= System::DELETE_LEVEL_1): ?>
                                                        <?php echo Html::button('审核意见', ['class' => 'btn btn-sm btn-primary', 'id' => 'open-modal-btn', 'data' => ['works-id' => $row->id]]); ?>
                                                    <?php endif; ?>

                                                    <a href="" class="btn btn-sm btn-primary">查看</a>
                                                </td>

                                            </tr>
                                            <?php if ($row->content): ?>
                                                <tr>
                                                    <td>备注</td>
                                                    <td colspan="5"><span><?php echo Html::encode($row->content); ?></span></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
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

<?php
Modal::begin([
    'title' => '创作审核',
    'id' => 'modal',
    'size' => 'modal-md',
    'options' => [
        'data-bs-backdrop' => 'static',
        'data-bs-keyboard' => 'false',
    ],
]);
?>
<?php echo Html::beginForm([''], 'post', ['id' => 'worksUploadForm']); ?>

<?php echo Html::hiddenInput('worksId', '', ['id' => 'worksId']); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <?php echo Html::dropDownList('state', '', ProjectConst::DEMAND_WORKS_AUDIT_SELECT, ['class' => 'form-control  form-select', 'id' => 'state']); ?>
        </div>

    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <?php echo Html::textarea('content', '', ['rows' => 3, 'class' => 'form-control']); ?>
        </div>
    </div>
    <div class="col-lg-12">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
    </div>

</div>


<?php

Html::endForm();
Modal::end();
?>

<?php
$replyUrl = Url::toRoute(['audit-save']);
$js = <<<JS
$(document).ready(function() {
    $('#open-modal-btn').click(function() {
        var worksId = $(this).data('works-id');
        $('#worksId').val(worksId); // 将参数填充到表单中
        $('#modal').modal('show');
    });

    $('#worksUploadForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{$replyUrl}',
            type: 'post',
            data: $(this).serialize(),
            success: function(data) {
                if(data.state=='success'){
                    $('#modal').modal('hide'); // 提交成功后手动关闭窗口
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });

        return false;
    });

    $('#modal').on('hidden.bs.modal', function() {
        $('#worksUploadForm')[0].reset(); // 清空表单内容
    });
});
JS;
$this->registerJs($js);