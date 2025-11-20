<?php
use app\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
$route = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="<?php echo Yii::$app->language; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo Html::encode($this->title); ?>- BageCMS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->head();?>
</head>

<body class="hold-transition  pace-primary">
    <?php $this->beginBody();?>
    <div style="padding-top:10px"></div>
    <div class="wrapper">
        <section class="content">
            <div class="container-fluid"> <?php echo $content; ?> </div>
        </section>
    </div>
    <?php $this->endBody();?>
</body>

</html>
<?php $this->endPage();?>