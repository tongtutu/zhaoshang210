<?php
use Yii;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap5\Html;
use kartik\file\FileInput;
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;

$this->title = '需求管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
AppAsset::addScript($this, Yii::$app->params['res.url'] . '/static/plugins/zebra_dialog/zebra_dialog.min.js');
AppAsset::addCss($this, Yii::$app->params['res.url'] . '/static/plugins/zebra_dialog/css/materialize/zebra_dialog.min.css');

?>
<div class="row">
    <div class="col-lg-12">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    提交作品 <?php echo $model->id; ?>
                </div>
                <div class="card-body">


                    <div class="row">
                        <div class="col-lg-12">

                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div>
                                    <?php echo Html::hiddenInput('id', $model->id); ?>
                                    <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交创作 ', ['class' => 'btn  btn-primary submit']); ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<?php

$js = <<<JS

window.onload = function() {
            
    window.parent.closeParentDialog();

// You can add additional logic after closing the dialog if needed

// For example, show a confirmation message
alert('Form submitted. Closing dialog.');


};

JS;
$this->registerJs($js);