<?php
$this->title = '会员';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '录入';
?>

<div class="row">
    <div class="col-lg-12"> <?php echo $this->render('_form', [
    'model' => $model,
]) ?> </div>
</div>