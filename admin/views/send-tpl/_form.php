<?php
use yii\bootstrap5\ActiveForm;
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
                        <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'title_alias')->textInput(['maxlength' => true]); ?> </div>
                </div>
                <?php echo $form->field($model, 'title_send')->textInput(['maxlength' => true]); ?>
                <?php echo $form->field($model, 'content_text')->textarea(['rows' => 6]); ?>
                <?php echo $form->field($model, 'content_html')->textarea(['rows' => 6]); ?>
                <?php echo $form->field($model, 'extend')->textarea(['rows' => 6]); ?>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'sorted')->textInput(['maxlength' => true]); ?> </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="callout callout-info">
                        <ul class="tips-body break-word">
                            <li>模板别名：程序执行过程中查找模板的依据。只能是A-Z a-Z 0-9的字符且不能重复</li>
                            <li>发送标题：一般用于邮件或其它需要带有标题的推送</li>
                            <li>文本内容：用于纯文本类的推送</li>
                            <li>扩展内容：每行一条，键和值之间用~隔开。如：
                                <p> name~小王<br />
                                    sex~女 </p>
                            </li>
                        </ul>
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