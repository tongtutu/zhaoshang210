<?php

use yii\helpers\Url;
use yii\helpers\Html;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;

$this->title = '修改手机号';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    更换手机
                </h3>
            </div>
            <?php $mobileForm = ActiveForm::begin(
                [
                    'class' => 'change-form',
                    'action' => Url::toRoute(['edit-mobile']),
                ]
            ); ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (Yii::$app->session->hasFlash('editMobile')): ?>
                            <div class="alert alert-success">
                                <?= Yii::$app->session->getFlash('editMobile'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $mobileForm->field($model, 'mobileOld')->textInput(['placeholder' => Utils::hidePhone($user->mobile), 'disabled' => true])->label('原手机号'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $mobileForm->field($model, 'mobile')->textInput(['class' => 'form-control mobile', 'placeholder' => '新手机号']); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <?= $mobileForm->field($model, 'code')->textInput(['class' => 'form-control', 'placeholder' => '验证码']); ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3 ">
                                            <label>&nbsp;</label>

                                            <?php echo Html::button(
                                                '获取验证码',
                                                [
                                                    'class' => 'btn btn-default hashRequest form-control',
                                                    'data-url' => Url::toRoute(['hash/request']),
                                                    'data-type' => System::HASH_CODE_EDIT_MOBILE,
                                                    'data-mob' => 'mobile',
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
                <?= Html::submitButton('提交修改', ['class' => 'btn btn-primary']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>