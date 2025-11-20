<?php

use yii\helpers\Url;
use yii\helpers\Html;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;

$this->title = '跟进维护';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
?>
<div class="row">
    <div class="col-lg-7">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">项目跟进基本信息</h1>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-">
                                <tbody>
                                    <tr>
                                        <th width="18%">信息编号</th>
                                        <td><?php echo $model->id; ?> </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>发布时间</th>
                                        <td><?php echo date('Y-m-d H:i:s', $model->created_at); ?></td>
                                    </tr>
                                    <tr>
                                        <th>项目名称</th>
                                        <td><?php echo Html::encode($model->project_name); ?></td>
                                    </tr>

                                    <tr>
                                        <th>项目阶段</th>
                                        <td><?php echo ProjectConst::STEPS[$model->steps]; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>维护类型</th>
                                        <td><?php echo ProjectConst::MAINTAIN_TYPE[$model->typeid]; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>下次跟进</th>
                                        <td><?php echo Html::encode($model->remind_time); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>状态</th>
                                        <td><?php echo ProjectConst::MAINTAIN_STATUS[$model->state]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>附件</th>
                                        <td>
                                            <div class="attachFile">
                                                <?php if ($attachFiles): ?>
                                                    <ul>
                                                        <?php foreach ($attachFiles as $key => $file): ?>
                                                            <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                                    target="_blank" class="btn btn-link "><i
                                                                        class="fa fa-paperclip"></i>
                                                                    <?php echo Html::encode($file->real_name); ?>

                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>情况描述</th>
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

        </div>
    </div>
    <div class="col-lg-5">
        <?php echo $this->render($model->source == System::SOURCE_CUSTOMER ? '/_include/project/customer' : '/_include/project/invest', [
            'project' => $project,

        ]); ?>
    </div>
</div>