<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use kartik\datetime\DateTimePicker;
?>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['maintain/validate']),
    ]
); ?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">跟进维护发布</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
           
                <div class="row">
                <div class="col-lg-6">
                        <?php echo $form->field($maintain, 'typeid')->dropDownList(ProjectConst::MAINTAIN_TYPE, ['id' => 'typeid'])->label($maintain->getAttributeLabel('typeid') . ' <span class="required-star">*</span>'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($maintain, 'steps')->dropDownList(ProjectConst::STEPS, ['id' => 'steps']); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo $form->field($maintain, 'remind_time')->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => '无需提醒请不要选择'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:00', // 仅显示日期和小时
                                'todayHighlight' => true,
                                'minView' => 'day', // 仅显示日期和小时
                                'startDate' => date('Y-m-d H:00', time() + 3600 * 24), // 开始日期
                                'endDate' => date('Y-m-d H:00', time() + 3600 * 24 * 30 * 6),
                                'todayBtn' => true, // 显示“今天”按钮
                        
                            ],
                        ]); ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <?php echo Html::hiddenInput('projectId', $model->id); ?>
        <?php echo Html::hiddenInput('source', $source); ?>
        <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交跟进维护 ', ['class' => 'submitBtn btn  btn-primary']); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<script>
    $('.submitBtn').click(function () {
        $.ajax({
            url: "<?php echo Url::toRoute(['maintain/save']); ?>",
            type: "POST",
            dataType: "json",
            data: $('form').serialize(),
            success: function (data) {
                new $.Zebra_Dialog(data.message, {
                    modal: false,
                });
                if (data.state == 'success') {
                    window.location.reload();
                }
            },
            error: function () {
                new $.Zebra_Dialog('提交失败，请重试', {
                    modal: false,
                });
            }
        });
        return false;
    });
</script>
<?php
$script = <<<JS
$(document).ready(function() {
    // 监听状态字段值的变化
    $('#steps').on('change', function() {
        if ($(this).val() === '4') {
            $('#bt_demand').prop('disabled', false);
        } else {
            $('#bt_demand').prop('disabled', true).val('2');
        }
    });

    // 页面加载时调用一次以确保初始状态正确
    $('#steps').change();
});
JS;

$this->registerJs($script);
?>