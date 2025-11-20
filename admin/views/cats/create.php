<?php
$this->title = '类型管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '录入';
?>

<div class="row">
    <div class="col-lg-12"> <?php echo $this->render('_form', [
    'model' => $model,
]) ?> </div>
</div>