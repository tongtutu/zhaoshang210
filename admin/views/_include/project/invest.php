<?php
use bagesoft\functions\ProjectFunc;
use yii\helpers\Html;
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">招商信息</h1>
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
                        <th>项目名称</th>
                        <td><?php echo Html::encode($project->project_name); ?></td>
                    </tr>
                    <tr>
                        <th>公司全称</th>
                        <td><?php echo Html::encode($project->company_name); ?></td>
                    </tr>
                    <tr>
                        <th>统一信用代码:</th>
                        <td><?php echo Html::encode($project->usci_code); ?></td>
                    </tr>
                    <tr>
                        <th>客户姓名</th>
                        <td><?php echo Html::encode(ProjectFunc::adminHideName($project->uid, $this->context->admin, $project->contact_name)); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>联系方式</th>
                        <td><?php echo Html::encode(ProjectFunc::adminHidePhone($project->uid, $this->context->admin, $project->contact_phone)); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
