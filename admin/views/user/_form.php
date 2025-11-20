<?php
use bagesoft\helpers\Utils;
use bagesoft\models\UserGroup;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [

        ],
    ]
);?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <?php if ($model->isNewRecord): ?>
                        <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]); ?>
                        <?php else: ?>
                        <div class="form-group">
                            <label class="control-label">用户名</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo Html::encode($model->username); ?> </p>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => $model->isNewRecord ? '不填写表示随机生成密码' : '']); ?> </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'mobile')->textInput(['maxlength' => true]); ?> </div>
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]); ?> </div>
                </div>
                <div class="row">

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'gid')->dropDownlist(ArrayHelper::map(UserGroup::find()->orderBy('id DESC')->all(), 'id', 'group_name'), []); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'state')->dropDownList(['1' => '正常', '2' => '锁定', '3' => '禁用']); ?>
                    </div>
                </div>
                <?php if (!$model->isNewRecord): ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">最后登录</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if ($model->last_login_at > 0): ?>
                                    <?php echo Utils::timeFmt($model->last_login_at); ?>
                                    <?php else: ?>
                                    -
                                    <?php endif;?>
                                    [ <?php echo $model->last_login_ip; ?>] </div>
                            </div>
                            <div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">登录失败：</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if ($model->login_error > 0): ?>
                                    <?php echo $model->login_error; ?>
                                    <?php else: ?>
                                    -
                                    <?php endif;?>
                                </div>
                            </div>
                            <div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-send-o"></i>录入 ' : '编辑', ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']); ?> <a
                href="<?php echo Url::to(['index']); ?>" class="btn"><i class="fa fa-history"></i>返回</a></div>
    </div>
</div>
<?php ActiveForm::end();?>