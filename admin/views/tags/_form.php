<?php

use bagesoft\models\Cats;
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
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'tag_name')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'catid')->dropDownList(ArrayHelper::map(Cats::find()->all(), 'id', 'cat_name')); ?>
                    </div>
                </div>


            </div>

        </div>

    </div>
    <div class="card-footer">
        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-send-o"></i>录入 ' : '编辑', ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']); ?> <a
                href="<?php echo Url::to(['index']); ?>" class="btn"><i class="fa fa-history"></i>返回</a> </div>
    </div>
    <?php ActiveForm::end();?>