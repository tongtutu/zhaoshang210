<?php
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\daterange\DateRangePicker;
$this->title = '受访记录';
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
                        <div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">姓名</span>
                                </div>
                                <?php echo Html::textInput('visitUser', $request->get('visitUser'), ['class' => 'form-control form-control-sm ', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">资源名称</span>
                                </div>
                                <?php echo Html::textInput('projectName', $request->get('projectName'), ['class' => 'form-control form-control-sm ', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">访问时间</span>
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
                                        'autoApply' => true,          // 选择日期后自动应用
                                        'autoUpdateInput' => true,    // 自动更新输入框的值
                                    ],
                                    'options' => [
                                        'class' => 'form-control form-control-sm custom-select',
                                        'readonly' => true,
                                    ]
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4"> <?php echo Html::hiddenInput('opt', 'search'); ?>
                            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i>搜索</button>
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
                                            <th>访问者</th>
                                            <th>资源名称</th>
                                            <th>访问时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <tr>
                                                    <td scope="row"> <?php echo $row->id; ?> </td>
                                                    <td><?php echo Html::encode($row->visit_user); ?></td>
                                                    <td>【<?php echo Html::encode($row->project_id); ?>】<?php echo Html::encode($row->project_name); ?>
                                                    </td>
                                                    <td>
                                                        <p> <?php echo date('Y-m-d H:i:s', $row->created_at); ?> </p>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php $opt = ' style="display:none"'; ?>
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