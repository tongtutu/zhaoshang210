<?php
use bagesoft\helpers\Utils;
use yii\helpers\Html;
$this->title = '操作日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '详情'];
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
    <table class="table table-bordered table-hover ">
      <colgroup>
      <col >
      <col>
      </colgroup>
      <tbody>
        <tr>
          <th>类型</th>
          <td><?php echo Html::encode($model->method); ?></td>
        </tr>
        <tr>
          <th>用户</th>
          <td><?php echo Html::encode($model->username); ?> &nbsp;&nbsp; [UID:<?php echo $model->user_id; ?>]</td>
        </tr>
        <tr>
          <th>IP</th>
          <td><?php echo Html::encode($model->ip); ?> [<?php echo $ipArr['country']; ?> </td>
        </tr>
        <tr>
          <th>时间</th>
          <td><?php echo date('Y-m-d H:i:s', $model->created_at); ?>&nbsp;&nbsp; <?php echo Utils::timeFmt($model->created_at); ?></td>
        </tr>
        <tr>
          <th>操作</th>
          <td><?php echo Html::encode($model->action_url); ?></td>
        </tr>
        <?php if ($model->user_agent): ?>
        <tr>
          <th>用户代理</th>
          <td><?php echo Html::encode($model->user_agent); ?></td>
        </tr>
        <?php endif;?>
        <?php if ($model->intro): ?>
        <tr>
          <th>描述</th>
          <td><?php echo Html::encode($model->intro); ?></td>
        </tr>
        <?php endif;?>
        <?php if ($model->datas): ?>
        <tr>
          <th>数据</th>
          <td><?php echo Utils::dump(unserialize($model->datas)); ?></td>
        </tr>
        <?php endif;?>
      </tbody>
    </table>
    </div>
    </div>
  </div>
</div>
