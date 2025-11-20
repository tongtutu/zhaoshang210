<?php
use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

AppAsset::addScript($this, '@web/static/plugins/datetimepicker/bootstrap-datetimepicker.js');
AppAsset::addScript($this, '@web/static/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js');
AppAsset::addCss($this, '@web/static/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css');

$this->registerJs('
$(function(){
  $("#hashlimit-typeis").change(
    function(){
      if($(this).val() == "2"){
        $("#expiredWrap").show();
      }else{
        $("#expiredWrap").hide();
      }
    }
  );
});
$("#hashlimit-expired_at").datetimepicker({
  language:  "zh-CN",
  weekStart: 1,
  todayBtn:  1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 0,
  showMeridian: 0,
  forceParse:true,
  minView:"hour",
  startDate:"' . date('Y-m-d H:i:s') . '",
  format:"yyyy-mm-dd hh:ii:ss"
});
', \yii\web\View::POS_END);
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
                <?php echo $form->field($model, 'target')->textInput(['maxlength' => true]) ?>
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo $form->field($model, 'typed')->dropDownList(['2' => '临时', '1' => '永久']) ?>
                            </div>
                            <div class="col-md-6" id="expiredWrap"
                                style="<?php if ($model->typed == 1): ?>display:none<?php endif?>">
                                <?php echo $form->field($model, 'expired_at')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-send-o"></i>录入 ' : '编辑', ['class' => 'btn btn-primary']) ?>
            <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']) ?> <a
                href="<?php echo Url::to(['index']) ?>" class="btn"><i class="fa fa-history"></i>返回</a> </div>
    </div>
</div>
<?php ActiveForm::end();?>