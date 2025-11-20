<?php
//use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use admin\assets\AppAsset;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\constant\ProjectConst;

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
                    <table class="table table- ">
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
                                <td><?php echo ProjectConst::DEMAND_STATUS[$model->state]; ?></td>
                            </tr>
                            <tr>
                                <th>附件</th>
                                <td>
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

                                    <?php if ($row->content): ?>
                                        <div class="timeline-body">
                                            <div>
                                                <?php echo nl2br(Html::encode($row->content)); ?>
                                            </div>


                                        </div>
                                    <?php endif; ?>
                                    <div class="timeline-footer ">

                                        <ul>
                                            <?php foreach ($attachFiles as $key => $file): ?>
                                                <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                        target="_blank"><i class="fa fa-paperclip"></i>
                                                        <?php echo Html::encode($file->real_name); ?> </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>


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
                                        <?php endif; ?>
                                        <?php if ($auditFiles): ?>
                                        <div class="timeline-footer ">
                                            <ul>
                                                <?php foreach ($auditFiles as $key => $file): ?>
                                                    <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                            target="_blank"><i class="fa fa-paperclip"></i>
                                                            <?php echo Html::encode($file->real_name); ?> </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>

</div>