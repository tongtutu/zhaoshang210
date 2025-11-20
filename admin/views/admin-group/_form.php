<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
if (!$model->isNewRecord && $model->acl && !is_array($model->acl)) {
    $model->acl = explode(',', $model->acl);
} else {
    $model->acl = [];
}
$this->registerCss("
    .one{padding-left: 0}
    .acl li{list-style: none}
    .two label{}
    .two label,.three label{font-weight: normal;padding-right:5px; }
");
?>
<script type="text/javascript">
function checknode(obj) {
    var chk = $("input[type='checkbox']");
    var count = chk.length;
    var num = chk.index(obj);
    var level_top = level_bottom = chk.eq(num).attr('level');
    for (var i = num; i >= 0; i--) {
        var le = chk.eq(i).attr('level');
        if (eval(le) < eval(level_top)) {
            chk.eq(i).attr("checked", true);
            var level_top = level_top - 1
        }
    }
    for (var j = num + 1; j < count; j++) {
        var le = chk.eq(j).attr('level');
        if (chk.eq(num).attr("checked") == true) {
            if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", true);
            else if (eval(le) == eval(level_bottom)) break
        } else {
            if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", false);
            else if (eval(le) == eval(level_bottom)) break
        }
    }
}
</script>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]
);?>

<div class="card">
    <div class="card-body">
        <?php echo $form->field($model, 'group_name')->textInput(['maxlength' => true]); ?>
        <?php
if ($model->getErrors('acl')) {
    $style = ' has-error';
    $error = '<div class="help-block">权限必须选择</div>';
}
?>
        <div class="form-group required<?php echo $style; ?>">
            <label class="control-label">权限选择</label>
            <?php echo $error; ?>
            <div class="row acl">
                <div class="col-md-12">
                    <ul class="one">
                        <?php foreach ((array) $tree as $key => $row): ?>
                        <?php if (!in_array($row['route'], ['site/index', 'public/logout'])): ?>
                        <li style="clear:both">
                            <label>
                                <input name="AdminGroup[acl][]" type="checkbox" value="<?php echo $row['route']; ?>"
                                    level='0' onclick='javascript:checknode(this);'
                                    <?php if (in_array($row['route'], $model->acl)): ?>checked="checked"
                                    <?php endif;?> />
                                <?php echo $row['title']; ?></label>
                            <?php if (count($row['child']) > 0): ?>
                            <ul class="two">
                                <?php foreach ($row['child'] as $skey => $srow): ?>
                                <li style="clear:both">
                                    <label>
                                        <input name="AdminGroup[acl][]" type="checkbox"
                                            value="<?php echo $srow['route']; ?>" level='1'
                                            onclick='javascript:checknode(this);'
                                            <?php if (in_array($srow['route'], $model->acl)): ?>checked="checked"
                                            <?php endif;?> />
                                        <?php echo $srow['title']; ?></label>
                                    <?php if (count($srow['child']) > 0): ?>
                                    <ul class="three">
                                        <?php foreach ($srow['child'] as $sskey => $ssrow): ?>
                                        <li style="float:left">
                                            <label>
                                                <input name="AdminGroup[acl][]" type="checkbox"
                                                    value="<?php echo $ssrow['route']; ?>" level='2'
                                                    onclick='javascript:checknode(this);'
                                                    <?php if (in_array($ssrow['route'], $model->acl)): ?>checked="checked"
                                                    <?php endif;?> />
                                                <?php echo $ssrow['title']; ?></label>
                                        </li>
                                        <?php endforeach;?>
                                    </ul>
                                    <?php endif;?>
                                </li>
                                <?php endforeach;?>
                            </ul>
                            <?php endif;?>
                        </li>
                        <?php endif;?>
                        <?php endforeach;?>
                    </ul>
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
</div>
<?php ActiveForm::end();?>