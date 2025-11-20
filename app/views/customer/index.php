<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use bagesoft\functions\TagsFunc;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\functions\ProjectFunc;
use bagesoft\constant\ProjectConst;
use bagesoft\constant\CustomerConst;
use kartik\daterange\DateRangePicker;

$this->title = '市场信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '列表';
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
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目名称</span>
                                </div>
                                <?php echo Html::textInput('projectName', $request->get('projectName'), ['class' => 'form-control form-control-sm ', 'placeholder' => '', 'id' => 'projectName']); ?>
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
                                <?php echo Html::dropDownList('steps', $request->get('steps'), ProjectConst::STEPS, ['class' => 'form-control  form-control-sm custom-select', 'prompt' => '-']); ?>
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

            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-primary" href="<?php echo Url::to(['create']); ?>">
                        <i class="fa fa-file-o"></i> 发布 </a>
                    <a class="btn btn-default" href="<?php echo Url::to(array_merge(['export-download'], Yii::$app->request->get())); ?>">
                        <i class="fa fa-file-o"></i> 导出 </a>
                </div>
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
                            <div class="table-responsive mailbox-messages"
                                style="overflow-x: auto; min-width: 1px; min-height:20vh">
                                <table class="table table-hover" style="width: max-content; min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>项目名称</th>
                                            <th style="min-width: 100px">合作伙伴</th>
                                            <th>客户</th>
                                            <th style="min-width: 180px">公司名称</th>
                                            <th>项目标签</th>
                                            <th>招投标经理</th>
                                            <th>状态</th>
                                            <th style="min-width: 80px">发布时间</th>
                                            <th style="min-width: 80px">最后跟进</th>
                                            <th>管理</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <td> <?php echo $row['id']; ?></td>
                                            <td><a
                                            href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode($row['project_name']); ?></a><br><span class="btn btn-default btn-xs"><?php echo ProjectConst::STEPS[$row['steps']]; ?></span> <span class="btn btn-default btn-xs"><?php echo ProjectConst::EXPAND_TYPE[$row['expand_type']]; ?></span>
                                            </td>
                                            <td><?php if ($row['uid'] == $this->context->user->id): ?><?php echo Html::encode($row['partner_name']) ?><?php else: ?><?php echo Html::encode($row['username']) ?><?php endif ?>
                                            </td>
                                            <td><?php echo Html::encode(ProjectFunc::hideName($row['uid'], $this->context->user->id, $row['realname'])); ?><br><?php echo System::STARS[$row['stars']]; ?>
                                            </td>
                                            <td><a
                                                    href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode(ProjectFunc::hideAll($row['uid'], $this->context->user->id, $row['company_name'])); ?><br><?php echo Html::encode(ProjectFunc::hideAll($row['uid'], $this->context->user->id, $row['usci_code'])); ?></a>
                                            </td>

                                            <td><?php echo TagsFunc::render($row['tags'], 'v'); ?></td>
                                            <td><?php if ($row['bt_manager_uid'] > 0): ?><?php echo $row['bt_manager_name']; ?><?php else: ?>
                                                -<?php endif; ?>
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

                                                        <?php if ($row['uid'] == $this->context->user->id): ?>
                                                        <!-- 发布者 -->
                                                        <li> <a class="dropdown-item"
                                                                href="<?php echo Url::to(['update', 'id' => $row['id']]); ?>">编辑</a>
                                                        </li>
                                                        <?php if ($row['partner_accept'] == ProjectConst::PARTNER_ACCEPT_APPOVE): ?>
                                                        <li> <a class="dropdown-item"
                                                                href="<?php echo Url::to(['demand/create', 'projectId' => $row['id'], 'source' => System::SOURCE_CUSTOMER]); ?>">提交需求</a>
                                                        </li>
                                                        <?php endif; ?>


                                                        <li><a class="dropdown-item"
                                                                href="<?php echo Url::to(['visit/index', 'projectId' => $row['id'], 'source' => System::SOURCE_CUSTOMER]); ?>"
                                                                target="_blank">访问记录</a></li>

                                                        <li><a class="dropdown-item bg-danger ajaxLink"
                                                                data-url="<?php echo Url::to(['delete', 'id' => $row['id']]); ?>"
                                                                data-message="确定删除吗？">
                                                                <?php if ($row['partner_accept'] == ProjectConst::PARTNER_ACCEPT_WAIT): ?>删除<?php else: ?>请求管理删除<?php endif; ?></a>
                                                        </li>

                                                        <?php elseif ($row['partner_uid'] == $this->context->user->id): ?>
                                                        <!-- 伙伴 -->
                                                        <?php if ($row['partner_accept'] == ProjectConst::PARTNER_ACCEPT_WAIT): ?>
                                                        <li> <a class="dropdown-item ajaxLink"
                                                                data-url="<?php echo Url::toRoute(['customer/partner/accept', 'id' => $row['id'], 'status' => System::APPROVE]); ?>">确认审核</a>
                                                        </li>
                                                        <?php elseif ($row['partner_accept'] == ProjectConst::PARTNER_ACCEPT_APPOVE): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo Url::to(['demand/create', 'projectId' => $row['id'], 'source' => System::SOURCE_CUSTOMER]); ?>">提交需求</a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php endif; ?>
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
<?php if (Yii::$app->session->hasFlash('publishSuccess')): ?>
<script>
new $.Zebra_Dialog(
    "发布成功", {
        auto_close: 3000,
        buttons: false,
        modal: false,
        position: ['center', 'middle']
    }
);
</script>
<?php endif; ?>