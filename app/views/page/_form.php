<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
?>
<?php $form = ActiveForm::begin(
    [
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            
        ],
    ]
);?>

<div class="card">
  <div class="card-header d-flex p-0">
    <ul class="nav nav-pills ml-auto p-2">
      <li class="nav-item"><a class="nav-link active" href="#base" data-toggle="tab">基本信息</a></li>
      <li class="nav-item"><a class="nav-link" href="#seo" data-toggle="tab">SEO优化</a></li>
      <li class="nav-item"><a class="nav-link" href="#ext" data-toggle="tab">功能扩展</a></li>
    </ul>
  </div>
  <div class="card-body"> 
    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="base">
        <div class="row">
          <div class="col-lg-6"> <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?> </div>
          <div class="col-lg-6"> <?php echo $form->field($model, 'title_second')->textInput(['maxlength' => true]) ?> </div>
        </div>
        <div class="row">
          <div class="col-lg-6"> <?php echo $form->field($model, 'title_alias')->textInput(['maxlength' => true]) ?> </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label" for="image">封面图片</label>
              <?php echo Html::fileInput('image', '', ['id' => 'image']) ?>
              <div class="help-block">2M以内</div>
            </div>
          </div>
          <div class="col-md-4">
            <?php if ($model->image): ?>
            <div class="row">
              <div class="col-md-3"> <a href="<?php echo Yii::$app->request->baseUrl ?>/<?php echo $model->image ?>" class="thumbnail" target="_blank"> <img src="<?php echo Yii::$app->request->baseUrl ?>/<?php echo $model->image ?>" height="50"> </a> </div>
            </div>
            <?php endif?>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12"> <?php echo $form->field($model, 'intro')->textarea(['rows' => 6]) ?> </div>
        </div>
        <?php echo $form->field($model, 'content')->widget('bagesoft\widget\ueditor\Ueditor', []) ?>
        <div class="row">
          <div class="col-lg-2"> <?php echo $form->field($model, 'sorted')->textInput(['maxlength' => true]) ?> </div>
          <div class="col-lg-2"> <?php echo $form->field($model, 'state')->dropDownList(['1' => '显示', '2' => '隐藏']) ?> </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="seo"> <?php echo $form->field($model, 'seo_title', ['template' => "{label}\n<div class=\"row\">\n<div class=\"col-md-6\">{input}\n</div>\n</div>\n<div>{error}</div>"])->textInput(['maxlength' => true]) ?> <?php echo $form->field($model, 'seo_keywords', ['template' => "{label}\n<div class=\"row\">\n<div class=\"col-md-6\">{input}\n</div>\n</div>\n<div>{error}</div>"])->textInput(['maxlength' => true]) ?> <?php echo $form->field($model, 'seo_description', ['template' => "{label}\n<div class=\"row\">\n<div class=\"col-md-6\">{input}\n</div>\n</div>\n<div>{error}</div>"])->textarea(['rows' => 6]) ?> </div>
      <div role="tabpanel" class="tab-pane" id="ext"> <?php echo $form->field($model, 'js', ['template' => "{label}\n<div class=\"row\">\n<div class=\"col-md-6\">{input}\n</div>\n</div>\n<div>{error}<div class=\"text-success\">无需填写 &lt;script&gt;&lt;/script&gt; 标签</div></div>"])->textarea(['rows' => 10]) ?> <?php echo $form->field($model, 'css', ['template' => "{label}\n<div class=\"row\">\n<div class=\"col-md-6\">{input}\n</div>\n</div>\n<div>{error}<div class=\"text-success\">无需填写 &lt;style&gt;&lt;/style&gt; 标签</div></div>"])->textarea(['rows' => 10]) ?> </div>
    </div>
  </div>
  <div class="card-footer">
    <div class="form-group">
      <div> <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-send-o"></i>录入 ' : '编辑', ['class' => 'btn btn-primary']) ?> <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']) ?> <a href="<?php echo Url::to(['index']) ?>" class="btn"><i class="fa fa-history"></i>返回</a></div>
    </div>
  </div>
</div>
<?php ActiveForm::end();?>
