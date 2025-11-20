<?php
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    密码修改
                </h3>
            </div>
            <?php $passForm = ActiveForm::begin(
    [
        'class' => 'change-form',
    ]
);?>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (Yii::$app->session->hasFlash('editPass')): ?>
                        <div class="alert alert-success">
                            <?=Yii::$app->session->getFlash('editPass');?>
                        </div>
                        <?php endif;?>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?=$passForm->field($model, 'password')->passwordInput();?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?=$passForm->field($model, 'repassword')->passwordInput();?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <?=$passForm->field($model, 'code')->textInput();?>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3 ">
                                            <label>&nbsp;</label>

                                            <?php echo Html::button('获取验证码', [
    'class' => 'btn btn-default hashRequest form-control',
    'data-url' => Url::toRoute(['hash/request']),
    'data-type' => System::HASH_CODE_EDIT_PASS,
],
); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?=Html::submitButton('提交修改', ['class' => 'btn btn-primary']);?>
            </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>