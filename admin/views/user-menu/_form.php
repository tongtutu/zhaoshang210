<?php
use bagesoft\models\UserMenu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

$class = new UserMenu;
$datalist = $class->roots()->all();
$level = 0;

$items[0] = '选择节点';
foreach ($datalist as $key => $value) {
    $items[$value->attributes['id']] = $value->attributes['title'];
    $children = $value->descendants()->all();
    foreach ($children as $child) {
        $string = '  ';
        $string .= @str_repeat('│  ', $child->level - $level - 1);
        if ($child->isLeaf() && !$child->next()->one()) {
            $string .= '└';
        } else {
            $string .= '├';
        }
        $string .= '─' . $child->title;
        $items[$child->id] = $string;
    }
}

if (!$model->isNewRecord) {
    $parent = $model->parent()->one();
}
?>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"row\">\n<div class=\"col-md-6\">{input}\n</div>\n</div>\n<div>{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]
);?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6"> <?php echo $form->field($model, 'parent_id')->dropDownList($items); ?>
                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'state')->dropDownList(['1' => '显示', '2' => '隐藏']) ?> </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'onmenu')->dropDownList(['1' => '显示', '2' => '隐藏']) ?> </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="callout callout-info">
                    <ul class="tips-body break-word">
                        <li>路由：是由controller/action 组成。如：post/index</li>
                        <li>显示在菜单：是指某些权限入口在子级页面中，无需展示在后台左侧菜单列表中</li>
                    </ul>
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