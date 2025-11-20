<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\datetime\DateTimePicker;
use kartik\daterange\DateRangePicker;
$this->title = '系统消息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
$request = Yii::$app->request;
$defaultStartDate = date('Y-m-d', strtotime('-7 days'));
$defaultEndDate = date('Y-m-d');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-2 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">姓名</span>
                                </div>
                                <?php echo Html::textInput('username', $request->get('username'), ['class' => 'form-control form-control-sm ', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">接收时间</span>
                                </div> <?php
                                echo DateRangePicker::widget([
                                    'name' => 'date',
                                    'convertFormat' => true,
                                    'value' => $request->get('date'),
                                    'pluginOptions' => [
                                        'timePicker' => false,
                                        'timePickerIncrement' => 15,
                                        'locale' => [
                                            'format' => 'Y-m-d'
                                        ],
                                        'maxDate' => date('Y-m-d', strtotime('+1 days')),
                                    ],
                                    'options' => [
                                        'class' => 'form-control form-control-sm custom-select',
                                        'readonly' => true,
                                    ]
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4"> <?php echo Html::hiddenInput('opt', 'search'); ?>
                            <button type="submit" class="btn btn-default btn-sm"><i
                                    class="fa fa-search"></i>搜索</button><a href="<?php echo Url::to(['index']); ?>"
                                class="btn btn-xs btn-link"> <i class="fa fa-undo"></i>取消</a>
                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card ">
            <?php if ($count > 0): ?>
                <div class="card-header">
                    <div class="card-title"><a class="btn btn-default" href="<?php echo Url::to(['export-download']); ?>">
                            <i class="fa fa-file-o"></i> 导出 </a></div>
                    <div class="card-tools">

                        共 <kbd> <?php echo $count; ?></kbd> 条记录

                    </div>
                </div>
            <?php endif; ?>
            <div class="card-body p-0">
                <div class="row">

                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover  ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>接收时间</th>
                                            <th>消息内容</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <tr>
                                                    <td><?php echo Html::encode($row->id); ?></td>
                                                    <td>
                                                        <?php echo date('Y-m-d H:i:s', $row->created_at); ?>
                                                    </td>
                                                    <td><?php echo Html::encode($row->content); ?></td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php $opt = ' style="display:none"'; ?>
                                            <td colspan="3" class="text-center text-primary">暂无记录</td>
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