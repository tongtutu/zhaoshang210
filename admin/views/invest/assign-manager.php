<?php

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '招商信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '任务分配';
?>
<div class="row">
    <div class="col-lg-9">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                    <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo Html::dropDownList('managerId', '', $managers, ['class' => 'form-control custom-select']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                        <?php echo Html::hiddenInput('id', $model->id); ?>
                            <button type="button" class="btn btn-default">提交</button>
                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                </div>
            </div>
        </div>


    </div>

</div>



<script>
$('.btn').click(function() {
    $.ajax({
        url: "<?php echo Url::toRoute(['assign-manager-save']); ?>",
        type: "POST",
        dataType: "json",
        data: $('form').serialize(),
        success: function(data) {
            alert(data.message);
            if (data.state == 'success') {
                window.location.href="<?php echo Url::toRoute(['invest/index'])?>";
            }
        },
        error: function() {
            alert('网络错误！');
        }
    });
    return false;
});
</script>