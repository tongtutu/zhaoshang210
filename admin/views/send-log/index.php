<?php
use yii\helpers\Url;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\LinkPager;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
$request = Yii::$app->request;
$this->title = '推送日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
AppAsset::addScript($this, '@web/static/plugins/datetimepicker/bootstrap-datetimepicker.js');
AppAsset::addScript($this, '@web/static/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js');
AppAsset::addCss($this, '@web/static/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css');
$this->registerJs('
$("#date").datetimepicker({
  language:  "zh-CN",
  weekStart: 1,
  todayBtn:  1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 2,
  forceParse: 0,
  showMeridian: 1,
  forceParse:true,
  minView:"month",
  startDate:"' . date('Y-m-d') . '",
  format:"yyyy-mm-dd"
});
', \yii\web\View::POS_END);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">类型</span>
                                </div>

                                <?php echo Html::dropdownList('channel', $request->get('channel'), System::MESSAGE_CHANNEL, ['class' => 'form-control   form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">日期</span>
                                </div>
                                <?php echo Html::textInput('date', $request->get('date'), ['class' => 'form-control form-control-sm', 'placeholder' => '', 'id' => 'date']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">目标</span>
                                </div>
                                <?php echo Html::textInput('target', $request->get('target'), ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3"> <?php echo Html::hiddenInput('opt', 'search'); ?>
                            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i>提交</button>
                            <a href="<?php echo Url::to(['index']); ?>" class="btn btn-xs btn-link"> <i
                                    class="fa fa-undo"></i>取消</a>
                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card ">
            <div class="card-header">
                <div class="card-title"> </div>
                <div class="card-tools">
                    <?php if ($count > 0): ?>
                    共 <kbd> <?php echo $count; ?></kbd> 条记录
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row">

                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover text-nowrap ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>来源/目标</th>
                                            <th>主题/内容</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <th scope="row"> <?php echo $row->id; ?> </th>
                                            <td><?php echo System::MESSAGE_CHANNEL[$row->channel]; ?>
                                                <p> <?php echo Html::encode($row->target); ?> </p>
                                            </td>
                                            <td><?php echo Html::encode($row->content); ?>
                                                <?php if ($row->created_at): ?>
                                                <p class="text-success"><small>
                                                        <?php echo date('Y-m-d H:i:s', $row->created_at); ?> [
                                                        <?php echo Utils::timeFmt($row->created_at); ?>]</small></p>
                                                <?php endif; ?>
                                                <?php echo Html::encode($row->callback) ?>
                                            </td>


                                        </tr>

                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <td colspan="4" class="text-center text-primary">暂无记录</td>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer p-0">
                <div class="mailbox-controls">
                    <div class="float-right"> <?php echo LinkPager::widget(
                        [
                            'pagination' => $pagination,
                            'options' => ['class' => 'pagination'],
                            'linkOptions' => ['class' => 'page-link'],
                            'activePageCssClass' => ' page-item',
                            'disabledPageCssClass' => ' active',
                            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                            'disableCurrentPageButton' => true,
                        ]
                    ); ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>