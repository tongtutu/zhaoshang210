<?php

$this->title = '市场信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="row">
    <div class="col-lg-12">
        <?php echo $this->render('_include/_form', [
    'model' => $model,
]); ?>
    </div>
</div>