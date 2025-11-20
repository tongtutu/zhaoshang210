<?php
//use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\widgets\LinkPager;
use bagesoft\functions\TagsFunc;
use bagesoft\functions\UserFunc;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\functions\ProjectFunc;
use yii\bootstrap5\ActiveForm;
use bagesoft\constant\ProjectConst;
use kartik\daterange\DateRangePicker;

$this->title = '市场信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '列表';
$request = Yii::$app->request;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">

                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目名称</span>
                                </div>
                                <?php echo Html::textInput('projectName', $request->get('projectName'), ['class' => 'form-control form-control-sm', 'placeholder' => '', 'id' => 'projectName']); ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">创建人</span>
                                </div>
                                <?php echo Html::textInput('username', $request->get('username'), ['class' => 'form-control form-control-sm', 'placeholder' => '', 'id' => 'username']); ?>
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">合作伙伴</span>
                                </div>
                                <?php echo Html::textInput('partner_name', $request->get('partner_name'), ['class' => 'form-control form-control-sm', 'placeholder' => '', 'id' => 'partner_name']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">发布时间</span>
                                </div>
                                <?php
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
                                        'placeholder' => '',
                                        'readonly' => true,
                                    ]
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">审核状态</span>
                                </div>
                                <?php echo Html::dropDownList('partnerAccept', $request->get('partnerAccept'), ProjectConst::PARTNER_ACCEPT, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">客户等级</span>
                                </div>
                                <?php echo Html::dropDownList('stars', $request->get('stars'), System::STARS, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目阶段</span>
                                </div>
                                <?php echo Html::dropDownList('steps', $request->get('steps'), ProjectConst::STEPS, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">拓展类型</span>
                                </div>
                                <?php echo Html::dropDownList('expandType', $request->get('expandType'), ProjectConst::EXPAND_TYPE, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3"> <?php echo Html::hiddenInput('opt', 'search'); ?>
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
                <div class="card-title">

                    <a class="btn btn-default" href="<?php echo Url::to(array_merge(['export-download'], Yii::$app->request->get())); ?>">
                        <i class="fa fa-file-o"></i> 导出 </a>
                </div>
                <div class="card-tools">
                    共 <kbd> <?php echo $count; ?></kbd> 条记录
                </div>
            </div><?php endif; ?>
            <div class="card-body p-0">
                <div class="row">

                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages" style="overflow-x: auto; min-width: 1px;">
                                <table class="table table-hover" style="width: max-content; min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>项目名称</th>
                                            <th style="width: 100px">创建人</th>
                                            <th style="width: 100px">合作伙伴</th>
                                            <th>客户</th>
                                            <th>公司名称</th>
                                            <th style="width: 250px">项目标签</th>
                                            <th>待分配</th>
                                            <th>状态</th>
                                            <th style="width: 150px">发布时间</th>
                                            <th style="width: 150px">最后跟进</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <td> <?php echo $row['id']; ?></td>
                                            <td><a
                                                    href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode($row['project_name']); ?></a><br><span
                                                    class="btn btn-default btn-xs"><?php echo ProjectConst::STEPS[$row['steps']]; ?></span>
                                                <span
                                                    class="btn btn-default btn-xs"><?php echo ProjectConst::EXPAND_TYPE[$row['expand_type']]; ?></span>
                                            </td>
                                            <td><?php echo Html::encode($row['username']) ?></td>
                                            <td><?php echo Html::encode($row['partner_name']) ?></td>
                                            <td><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $row['realname'])); ?><br><?php echo System::STARS[$row['stars']]; ?>
                                            </td>

                                            <td><a href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $row['company_name'])); ?>
                                                    <br><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $row['usci_code'])); ?></a>
                                            </td>

                                            <td><?php echo TagsFunc::render($row['tags'], 'v'); ?></td>
                                            <td><?php if ($row['bt_manager_uid'] > 0): ?><?php echo $row['bt_manager_name']; ?><?php else: ?>
                                                待分配<?php endif; ?>
                                            </td>
                                            <td><?php echo ProjectConst::PARTNER_ACCEPT[$row['partner_accept']]; ?>

                                            </td>
                                            <td><?php echo date('Y-m-d H:i', $row['created_at']); ?>
                                            </td>
                                            <td><?php if ($row['maintain_at'] > 0): ?>
                                                <?php echo date('Y-m-d H:i', $row['maintain_at']); ?>
                                                <?php endif ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-width-sm"> <a type="button"
                                                        class="btn btn-default btn-sm"
                                                        href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>">详情</a>
                                                    <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a class="dropdown-item open-modal"
                                                                data-project-id='<?php echo $row['id'] ?>'>分配招投标</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item transferModal"
                                                                data-project-id='<?php echo $row['id'] ?>'>过户</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="<?php echo Url::to(['update', 'id' => $row['id']]); ?>">编辑</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item  ajaxLink"
                                                                data-url="<?php echo Url::to(['delete', 'id' => $row['id']]); ?>">删除<?php if ($row['bt_request'] == System::YES): ?>
                                                                <span class="badge bg-danger">请求删除</span>
                                                                <?php endif ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <?php $opt = ' style="display:none"'; ?>
                                        <td colspan="11" class="text-center text-primary">暂无记录</td>
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
                    <div class="float-right">
                        <?php echo LinkPager::widget(
                            [
                                'pagination' => $pagination,
                                'options' => ['class' => 'pagination'],
                                'linkOptions' => ['class' => 'page-link'],
                                'activePageCssClass' => ' page-item',
                                'disabledPageCssClass' => ' active',
                                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                                'disableCurrentPageButton' => true,
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $this->render('/_include/assignBtManager', [
    'project' => $project,
    'action' => ['customer/assign-bt-manager']
]) ?>

<?php echo $this->render('/_include/transfer', [
    'project' => $project,
    'action' => ['customer/transfer']
]) ?>