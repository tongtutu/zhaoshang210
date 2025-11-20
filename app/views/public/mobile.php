<?php
use bagesoft\constant\System;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
$template = '{label}{beginWrapper}{input}{hint}{error}{endWrapper}';
$this->title = '登录';
?>

<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary card-transparent">
        <div class="card-header text-center">
            <h4 class="title">旗联云-用户端</h4>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form-signin']]); ?>
            <div class="row">
                <div class="col-lg-12">

                    <?php echo $form->field($model, 'mobile', ['template' => $template])->textInput(['class' => 'form-control mobile', 'placeholder' => '手机号'])->label(false); ?>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'code', ['template' => $template])->textInput(['class' => 'form-control', 'placeholder' => '验证码'])->label(false); ?>
                        </div>
                        <div class="col-lg-6">
                            <?php echo Html::button(
                                '获取验证码',
                                [
                                    'class' => 'btn btn-default hashRequest form-control',
                                    'data-url' => Url::toRoute(['hash/request']),
                                    'data-type' => System::HASH_CODE_LOGIN,
                                    'data-mob' => 'mobile',
                                    'data-wait' => 60,
                                ],
                            ); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">

                    <?php echo $form->field($model, 'keeplogin')->checkbox(['label' => '保持登录(7天)']); ?>

                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-block">登录系统</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="social-auth-links text-center mt-2 mb-3">
                <a href="<?php echo Url::toRoute(['login']); ?>" class="btn btn-block btn-">
                    通过用户名登录
                </a>
            </div>
            <!-- /.social-auth-links -->
            <p class="mb-1">
                <a href="<?php echo Url::toRoute(['reset-password']); ?>">重置密码</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>