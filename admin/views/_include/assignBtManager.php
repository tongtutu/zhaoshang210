<?php
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use bagesoft\functions\UserFunc;
use yii\bootstrap5\ActiveForm;

// 模态框
Modal::begin([
    'title' => '分配招投标',
    'id' => 'assignBTModal',
    'size' => 'modal-lg', // 设置模态框大小为大尺寸
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
]);

$form = ActiveForm::begin([
    'id' => 'assignBTForm',
    'action' => $action,
]);
?>


<div class="card card-primary ">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->field($project, 'bt_manager_uid')->dropDownList(UserFunc::getBTManagers())->label('请选择'); ?>
                
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?php echo Html::hiddenInput('projectId', '', ['id' => 'projectId']); ?>
        <?php echo Html::submitButton('提交 ', ['class' => 'submitBtn btn  btn-primary']); ?>
    </div>
</div>

<?php


ActiveForm::end();

Modal::end();


$script = <<<JS
$(document).ready(function() {
    $('.open-modal').click(function() {
        var projectId = $(this).data('project-id');
        $('#projectId').val(projectId);
        $('#assignBTForm')[0].reset(); // 重置表单内容
        $('#assignBTModal').modal('show');
    });

    $('#assignBTForm').on('beforeSubmit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            data: $(this).serialize(),
            success: function(data) {
                // 在这里处理成功提交后的逻辑
                if(data.state=='error'){
                    alert(data.message);
                }else{
                    $('#assignBTModal').modal('hide');
                    window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('提交失败，请重试');
                return false;
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });

  
});
JS;

$this->registerJs($script);
?>