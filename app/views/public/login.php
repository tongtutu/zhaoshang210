<?php
use yii\bootstrap5\ActiveForm;
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

                    <?php echo $form->field($model, 'username', ['template' => $template])->textInput(['class' => 'form-control', 'placeholder' => '用户名'])->label(false); ?>
                </div>
                <div class="col-lg-12">
                    <?= $form->field($model, 'password', ['template' => $template])->passwordInput(['class' => 'form-control', 'placeholder' => '输入密码'])->label(false); ?>
                </div>
                <div class="col-lg-12">

                    <?= $form->field($model, 'keeplogin')->checkbox(['label' => '保持登录(7天)', 'checked' => $model->keeplogin]); ?>

                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-block">登录系统</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="social-auth-links text-center mt-2 mb-3">
                <a href="<?php echo Url::toRoute(['mobile']); ?>" class="btn btn-block btn-">
                    通过手机、验证码登录
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