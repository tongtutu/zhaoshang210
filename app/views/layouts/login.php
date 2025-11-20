<?php
use app\assets\LoginAsset;
use yii\helpers\Html;
LoginAsset::register($this);
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="<?php echo Yii::$app->language; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head();?>
</head>

<body class="hold-transition login-page"
    style="background: url(<?php echo Yii::$app->params['res.url']; ?>/static/app/img/login-bg.jpg?v=<?php echo Yii::$app->params['res.ver'] ?>) no-repeat fixed;background-size: cover;">
    <?php $this->beginBody();?>
    <?php echo $content; ?>
    <?php $this->endBody();?>
</body>

</html>
<?php $this->endPage();?>