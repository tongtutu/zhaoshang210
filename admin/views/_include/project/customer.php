<?php
use yii\helpers\Html;
use bagesoft\constant\System;
use bagesoft\functions\ProjectFunc;
use bagesoft\constant\ProjectConst;
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">市场信息</h1>
    </div>
    <div class="card-body">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th width="30%">信息编号</th>
                        <td><?php echo $project->id; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <th>发布时间</th>
                        <td><?php echo date('Y-m-d H:i:s', $project->created_at); ?></td>
                    </tr>

                    <tr>
                        <th>客户姓名</th>
                        <td><?php echo Html::encode(ProjectFunc::adminHideName($this->context->admin, $project->realname)); ?>
                            <?php echo System::STARS[$project->stars]; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>联系方式</th>
                        <td><?php echo Html::encode(ProjectFunc::adminHidePhone($this->context->admin, $project->phone)); ?><br><?php echo Html::encode(ProjectFunc::adminHidePhone($this->context->admin, $project->phone1)); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>项目阶段</th>
                        <td><?php echo ProjectConst::STEPS[$project->steps]; ?>
                            <?php if ($project->maintain_at > 0): ?>
                                <small>最后跟进(<?php echo date('Y-m-d H:i:s', $project->maintain_at); ?>)</small><?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <th>公司全称</th>
                        <td><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $project->company_name)); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>统一信用代码:</th>
                        <td><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $project->usci_code)); ?>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>