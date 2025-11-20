<?php

$this->title = '招商信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '录入';
?>
<div class="row">
    <div class="col-lg-12">

        <?php echo $this->render('_form', [
    'model' => $model,
    'hasManager' => $hasManager,

]); ?>
    </div>
</div>