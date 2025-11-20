<?php
$this->title = '需求管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '提交';
?>
<div class="row">
    <div class="col-lg-12">
        <?php echo $this->render('_form', [
    'project' => $project,
    'source' => $source,
    'model' => $model,
]); ?>
    </div>
</div>