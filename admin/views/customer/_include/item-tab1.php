<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use bagesoft\func\TagsFunc;
use bagesoft\constant\System;
use bagesoft\func\ProjectFunc;
use bagesoft\constant\ProjectConst;
$this->title = '市场信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
?>
<div class="row">
    <div class="col-lg-9">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width:15%">信息编号</th>
                                        <td><?php echo $model->id; ?> <small>访问:<?php echo $model->views; ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>发布时间</th>
                                        <td><?php echo date('Y-m-d H:i:s', $model->created_at); ?></td>
                                    </tr>

                                    <tr>
                                        <th>客户姓名:</th>
                                        <td><?php echo Html::encode(ProjectFunc::adminHideName($this->context->admin->gid, $model->realname)); ?>
                                            <?php echo System::STARS[$model->stars]; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>联系方式:</th>
                                        <td><?php echo Html::encode(ProjectFunc::adminHidePhone($this->context->admin->gid, $model->phone)); ?><br><?php echo Html::encode(ProjectFunc::adminHidePhone($this->context->admin->gid, $model->phone1)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>联系地址:</th>
                                        <td><?php if ($model->province): ?><?php echo Html::encode($model->province); ?><?php endif; ?><?php if ($model->province): ?>
                                                <?php echo Html::encode($model->city); ?><?php endif; ?><?php if ($model->province): ?>
                                                <?php echo Html::encode($model->area); ?><?php endif; ?>
                                            <?php echo Html::encode(ProjectFunc::adminHidePhone($this->context->admin->gid, $model->address)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>项目阶段</th>
                                        <td><?php echo ProjectConst::STEPS[$model->steps]; ?>
                                            <?php if ($model->maintain_at > 0): ?>
                                                <small>最后跟进(<?php echo date('Y-m-d H:i:s', $model->maintain_at); ?>)</small><?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>项目类型</th>
                                        <td><?php echo TagsFunc::render($model->tags); ?></td>
                                    </tr>
                                    <tr>
                                        <th>公司全称:</th>
                                        <td><?php echo Html::encode($model->company_name); ?></td>
                                    </tr>
                                    <tr>
                                        <th>统一信用代码:</th>
                                        <td><?php echo Html::encode($model->usci_code); ?></td>
                                    </tr>
                                    <tr>
                                        <th>项目介绍:</th>
                                        <td><?php echo $model->content; ?></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h1 class="card-title">跟进维护</h1>
                    </div>
                    <div class="card-body">



                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 ">
                                <div class="row">
                                    <div class="col-12">

                                        <?php if ($maintains['datalist']): ?>
                                            <?php foreach ($maintains['datalist'] as $key => $row): ?>
                                                <div class="post">
                                                    <div class="-block">
                                                        <strong class="text-primary">
                                                            <?php echo $row['realname']; ?>
                                                        </strong>
                                                        <span class="description">
                                                            在 <?php echo date('Y-m-d H:i', $row['created_at']); ?>
                                                            发布项目动态</span> <span
                                                            class="badge badge-success "><?php echo ProjectConst::STEPS[$row['steps']]; ?></span>
                                                        <span
                                                            class="badge badge-primary"><?php echo ProjectConst::MAINTAIN_STATUS[$row['state']]; ?></span>

                                                    </div>
                                                    <div>
                                                        <?php echo nl2br(Html::encode($row['content'])); ?>
                                                    </div>
                                                    <p>
                                                        <?php if ($row['prove_file']): ?>
                                                            <a href="<?php echo Yii::$app->params['res.url']; ?>/<?php echo Html::encode($row['prove_file']); ?>"
                                                                class="link-black text-sm" target="_blank"><i
                                                                    class="fas fa-link mr-1"></i> 证明</a><?php endif; ?>
                                                        <?php if ($row['report_file']): ?>
                                                            <a href="<?php echo Yii::$app->params['res.url']; ?>/<?php echo Html::encode($row['report_file']); ?>"
                                                                class="link-black text-sm" target="_blank"><i
                                                                    class="fas fa-link mr-1"></i>报告</a><?php endif; ?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        use bagesoft\constant\System;
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mailbox-controls">
                        <div class="float-right">
                            <?php echo LinkPager::widget(
                                [
                                    'pagination' => $maintains['pagination'],
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
                <!-- /.card -->
            </div>
        </div>

    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">合作伙伴</h5>
            </div>
            <div class="card-body">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>姓名</th>
                                <td><?php echo Html::encode($partner->username); ?></td>
                            </tr>

                            <tr>
                                <th>手机</th>
                                <td><?php echo Html::encode($partner->mobile); ?></td>
                            </tr>

                            <tr>
                                <th>状态</th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>