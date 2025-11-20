<?php
use yii\helpers\Url;
use app\assets\AppAsset;
use bagesoft\helpers\Utils;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use PhpOffice\PhpSpreadsheet\Shared\Date;
$this->title = '首页';
$this->params['breadcrumbs'][] = '系统首页';
$user = $this->context->user;

?>

<div class="row">
    <div class="col-lg-5">
        <div class="card  card-outline card-warning">
            <div class="card-header card-success">
                <h1 class="card-title">信息统计</h1>
            </div>
            <div class="card-body table-responsive p-0" id="boxcontent">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <th width="30%">统计项目</th>
                        <th>发布/分配</th>
                        <th>待审/待确认</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>市场信息</td>
                            <td><a href="<?php echo Url::toRoute(['customer/index']); ?>"
                                    class="text-primary"><?php echo $customerNum; ?></a></td>
                            <td>
                                <?php if ($customerWaitAcceptNum > 0): ?>
                                    <a href="<?php echo Url::toRoute(['customer/index']); ?>"
                                        class="badge badge-danger"><?php echo $customerWaitAcceptNum; ?></a>
                                <?php else: ?>
                                    <?php echo $customerWaitAcceptNum; ?>
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr>
                            <td>招商信息</td>
                            <td><a href="<?php echo Url::toRoute(['invest/index']); ?>"
                                    class="text-primary"><?php echo $investNum; ?></a></td>
                            <td>-</td>
                        </tr>

                        <tr>
                            <td>跟进维护</td>
                            <td><a href="<?php echo Url::toRoute(['maintain/index']); ?>"
                                    class="text-primary"><?php echo $maintainNum; ?></a></td>
                            <td>
                                <?php if ($maintainWaitAuditNum > 0): ?>
                                    <a href="<?php echo Url::toRoute(['maintain/index', 'state' => ProjectConst::MAINTAIN_STATUS_WAIT]); ?>"
                                        class="badge badge-danger"><?php echo $maintainWaitAuditNum; ?></a>
                                <?php else: ?>
                                    <?php echo $maintainWaitAuditNum; ?>
                                <?php endif ?>

                            </td>
                        </tr>

                        <tr>
                            <td>需求信息</td>
                            <td><a href="<?php echo Url::toRoute(['demand/index']); ?>"><?php echo $demandNum; ?></a>
                            </td>
                            <td>
                                <?php if ($demandWorksWaitAcceptNum > 0): ?>

                                    <a href="<?php echo Url::toRoute(['demand/index', 'state' => ProjectConst::DEMAND_STATUS_WAIT_AUDIT]); ?>"
                                        class="badge badge-danger"><?php echo $demandWorksWaitAcceptNum; ?></a>
                                <?php else: ?>
                                    <?php echo $demandWorksWaitAcceptNum; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>创作信息</td>
                            <td><a href="<?php echo Url::toRoute(['demand/index']); ?>"
                                    class="text-primary"><?php echo $workerNum; ?></a></td>
                            <td>
                                <?php if ($workerWaitAcceptNum > 0): ?> <a
                                        href="<?php echo Url::toRoute(['demand/index']); ?>" class="badge badge-danger">
                                        <?php echo $workerWaitAcceptNum; ?></a>
                                <?php else: ?>     <?php echo $workerWaitAcceptNum; ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>招投标</td>
                            <td><a href="<?php echo Url::toRoute(['customer/index']); ?>"
                                    class="text-primary"><?php echo $btMgrNum; ?></a></td>
                            <td>
                                -
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">我的备忘录</h3>
                <div class="card-tools">
                    <span class="badge badge-success " id="memo-result" <?php if ($user->memo_updated_at <= 0): ?>style="display:none" <?php endif; ?>>
                        <?php if ($user->memo_updated_at > 0): ?>
                            <?php echo Utils::timeFmt($user->memo_updated_at); ?>更新
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($user, 'memo')->textArea(['rows' => 7, 'class' => 'form-control'])->label(false); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</div>
<?php
$url = Url::toRoute(['home/edit-memo']);
if ($customerWaitAcceptNum > 0 || $maintainWaitAuditNum > 0 || $demandWorksWaitAcceptNum > 0 || $workerWaitAcceptNum > 0) {
    $echoTips = 1;
} else {
    $echoTips = 0;
}
$script = <<<JS
    var echoTips = {$echoTips};
    // 显示 Zebra_Dialog
    function showZebraDialog() {
        $.Zebra_Dialog('', {
            source: {
                inline: $("#boxcontent").html()
            },
            position: ['center', 'middle'],
            width: '50%',
            backdrop_close: false,
            backdrop_opacity: 0.5,
            title: '信息统计',
            modal:false,
            type:false,
            buttons:  [
                {
                    caption: '关闭'
                },
                {
                    caption: '今天不再提醒',
                    callback: function() {
                        localStorage.setItem('zebraDialogLastClosedTimestamp', Date.now()); // 保存当前时间戳
                    }
                }
            ]
        });
    }

    function checkShowZebraDialog() {
        var lastClosedTimestamp = localStorage.getItem('zebraDialogLastClosedTimestamp');
        var todayTimestamp = new Date().setHours(0, 0, 0, 0);
        if (!lastClosedTimestamp || todayTimestamp > lastClosedTimestamp) {
            showZebraDialog();
        }
    }
    // 页面加载完成后，检查是否需要显示提示信息
    echoTips==1 && checkShowZebraDialog();

$(document).ready(function() {
    $('#user-memo').on('blur', function() {
      var data = $(this).serializeArray();
        $.post({
            url: '{$url}',
            data: data,
            success: function(response) {
                $("#memo-result").show().text(response.message);
            }
        });
    });
});
JS;
$this->registerJs($script);
?>