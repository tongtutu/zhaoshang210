<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Oops!' . $code;
?>

<div class="row">
  <div class="col-md-12">
    <h2 class="text-yellow"><i class="fa fa-warning text-yellow"></i> Oops!</span> </h2>
    <h4><?php echo $code ?> ：<?php echo Html::encode($message) ?></h4>
    <div>您可以返回 <a href=" <?php echo Url::to(['site/index']) ?>" class="btn">系统首页</a> 或 <a href="javascript:void(0);" onclick="history.back();"  class="btn">上一页</a> 进行再次尝试。</div>
  </div>
</div>
