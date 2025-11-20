<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '自定义设置';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['diy/index']];
?>

<div class="row">
  <div class="col-lg-12"> <?php echo Html::beginForm(['diy'], 'post', ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) ?> <?php echo $this->render('/_include/diy-attr', ['attrs' => $attrs, 'datas' => $datas, 'pre' => 'diy']) ?>
    <div class="row">
      <div class="col-md-12"><?php echo Html::button('<i class="fa fa-send-o"></i>提交', ['class' => 'btn btn-primary', 'type' => 'submit']) ?> <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']) ?> <a href="<?php echo Url::to(['attr/create']) ?>" class="btn">添加更多属性</a></div>
    </div>
    <?php echo Html::endForm() ?> </div>
</div>
