<?php
use bagesoft\helpers\Utils;
use yii\helpers\Html;
$this->title = '推送日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover table-condensed">
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>动作</th>
                            <td><?php echo Html::encode($model->action) ?></td>
                        </tr>
                        <tr>
                            <th>发送帐户</th>
                            <td><?php echo Html::encode($model->account) ?></td>
                        </tr>
                        <tr>
                            <th>网关</th>
                            <td><?php echo Html::encode($model->gateway) ?></td>
                        </tr>
                        <tr>
                            <th>来源</th>
                            <td><?php echo Html::encode($model->source) ?></td>
                        </tr>
                        <tr>
                            <th>目标</th>
                            <td><?php echo Html::encode($model->target) ?></td>
                        </tr>
                        <tr>
                            <th>内容</th>
                            <td><?php echo Html::encode($model->content) ?></td>
                        </tr>
                        <?php if ($model->callback): ?>
                        <tr>
                            <th>回调结果</th>
                            <td><?php echo Utils::dump(unserialize($model->callback)) ?></td>
                        </tr>
                        <?php endif?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>