<?php
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
$template = '{label}{beginWrapper}{input}{hint}{error}{endWrapper}';
$this->title = '重置密码';
?>

<div class="login-box">
    <div class="card card-outline card-primary card-transparent">
        <div class="card-header text-center">
            <h4 class="title">旗联云-用户端</h4>
        </div>
        <div class="card-body">

            <?php if (Yii::$app->session->hasFlash('resetPassword')): ?>
                <div class="alert alert-success">
                    <?= Yii::$app->session->getFlash('resetPassword'); ?>
                </div>
            <?php endif; ?>
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form-signin']]); ?>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $form->field($model, 'mobile', ['template' => $template])->textInput(['class' => 'form-control mobile', 'placeholder' => '手机号'])->label(false); ?>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'code', ['template' => $template])->textInput(['class' => 'form-control', 'placeholder' => '手机验证码'])->label(false); ?>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 ">
                                <?php echo Html::button(
                                    '获取验证码',
                                    [
                                        'class' => 'btn btn-default hashRequest form-control',
                                        'data-url' => Url::toRoute(['hash/request']),
                                        'data-type' => System::HASH_CODE_RESET_PASS,
                                        'data-mob' => 'mobile',
                                        'data-wait' => 60,
                                    ],
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <!-- /.col -->
                    <button type="submit" class="btn btn-primary btn-block">提交密码找回</button>
                    <!-- /.col -->
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <p class="mt-3 mb-1">
                <a href="<?php echo Url::toRoute(['login']); ?>">登录系统</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>