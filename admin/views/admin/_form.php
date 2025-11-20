<?php
use bagesoft\helpers\Utils;
use bagesoft\models\AdminGroup;
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
); ?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'mobile')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'gid')->dropDownList(ArrayHelper::map(AdminGroup::find()->where('id!=1')->all(), 'id', 'group_name')); ?>
                    </div>
                </div>
                <div class="row">

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'state')->dropDownList(['1' => '正常', '2' => '锁定']); ?>
                    </div>
                </div>
                <?php if (!$model->isNewRecord): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">最后登录</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if ($model->last_login_at > 0): ?>
                                            <?php echo Utils::timeFmt($model->last_login_at); ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                        [ <?php echo $model->last_login_ip; ?>]
                                    </div>
                                </div>
                                <div>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">登录失败：</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if ($model->login_error > 0): ?>
                                            <?php echo $model->login_error; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-send-o"></i>录入 ' : '编辑', ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']); ?> <a
                href="<?php echo Url::to(['index']); ?>" class="btn"><i class="fa fa-history"></i>返回</a>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>