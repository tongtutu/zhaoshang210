<?php
use bagesoft\helpers\Utils;
use yii\helpers\Html;
$this->title = '校验码';
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
                            <th>TOKEN</th>
                            <td><?php echo Html::encode($model->token); ?></td>
                        </tr>
                        <tr>
                            <th>目标</th>
                            <td><?php echo Html::encode($model->target); ?></td>
                        </tr>
                        <tr>
                            <th>HASH</th>
                            <td><?php echo Html::encode($model->hash); ?></td>
                        </tr>
                        <tr>
                            <th>状态</th>
                            <td><?php if ($model->state == 1): ?>
                                未使用
                                <?php else: ?>
                                已使用
                                <?php endif;?></td>
                        </tr>
                        <?php if ($model->state == 2): ?>
                        <tr>
                            <th>使用时间</th>
                            <td><?php echo date('Y-m-d H:i:s', $model->used_at); ?></td>
                        </tr>
                        <?php endif;?>
                        <?php if ($model->params): ?>
                        <tr>
                            <th>参数</th>
                            <td><?php echo Utils::dump(unserialize($model->params)); ?></td>
                        </tr>
                        <?php endif;?>
                        <tr>
                            <th>创建时间</th>
                            <td><?php echo date('Y-m-d H:i:s', $model->created_at); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>