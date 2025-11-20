<?php
//use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use bagesoft\functions\TagsFunc;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\functions\ProjectFunc;
use bagesoft\constant\ProjectConst;
use kartik\daterange\DateRangePicker;

$this->title = '招商信息';
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
                                    <span class="input-group-text">发布时间</span>
                                </div><?php
                                echo DateRangePicker::widget([
                                    'name' => 'date',
                                    'convertFormat' => true,
                                    'value' => $request->get('date'),
                                    'pluginOptions' => [
                                        'timePicker' => false,
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
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目阶段</span>
                                </div>
                                <?php echo Html::dropDownList('steps', $request->get('steps'), ProjectConst::STEPS, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目渠道</span>
                                </div>
                                <?php echo Html::dropDownList('channelId', $request->get('channelId'), ProjectConst::CHANNEL, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">所属考核项目</span>
                                </div>
                                <?php echo Html::dropDownList('assess', $request->get('assess'), TagsFunc::getTagsList(ProjectConst::PROJECT_ASSESS_TAG_ID), ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
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
                                        <th>ID</th>
                                        <th>项目名称</th>
                                        <th>创建人</th>
                                        <th>公司名称</th>
                                        <th>项目经理</th>
                                        <th style="width: 200px">项目标签</th>
                                        <th>发布时间</th>
                                        <th>最后跟进时间</th>
                                        <th></th>

                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <td> <?php echo $row['id']; ?></td>
                                            <td><a href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode($row['project_name']); ?></a><br><span class="btn btn-default btn-xs"><?php echo ProjectConst::CHANNEL[$row['channel_id']]; ?></span> <span class="btn btn-default btn-xs"><?php echo ProjectConst::STEPS[$row['steps']]; ?></span> <?php if($row['project_assess']):?><span class="btn btn-default btn-xs"><?php echo TagsFunc::getTagsName($row['project_assess']); ?></span><?php endif?>
                                            </td>
                                            <td><?php echo Html::encode($row['username']); ?></td>
                                            <td><a href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $row['company_name'])); ?>
                                                    <br><?php echo Html::encode(ProjectFunc::adminHideAll($this->context->admin, $row['usci_code'])); ?></a>
                                            </td>
                                            <td><?php if ($row['manager_uid'] > 0): ?><?php echo $row['manager_name']; ?><?php else: ?>
                                                待分配<?php endif; ?>
                                            </td>
                                            <td><?php echo TagsFunc::render($row['tags'], 'v'); ?></td>

                                           
                                            <td><?php echo date('Y-m-d H:i:s', $row['created_at']); ?>
                                            </td>
                                            <td> <?php echo Utils::timeFmt($row['maintain_at']); ?></td>

                                            <td>
                                                <div class="btn-group btn-width-sm"> <a type="button"
                                                        class="btn btn-default btn-sm"
                                                        href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>">详情</a>
                                                    <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo Url::to(['update', 'id' => $row['id']]); ?>">编辑</a>
                                                        </li>
                                                        <li> <a class="dropdown-item <?php if ($row['deleted'] == System::DELETE_LEVEL_2): ?>bg-danger<?php else: ?>bg-default<?php endif ?> ajaxLink"
                                                                data-url="<?php echo Url::to(['delete', 'id' => $row['id']]); ?>">删除</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item transferModal"
                                                                data-project-id='<?php echo $row['id'] ?>'>过户</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <?php $opt = ' style="display:none"'; ?>
                                        <td colspan="10" class="text-center text-primary">暂无记录</td>
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
    'action' => ['invest/assign-bt-manager']
]) ?>
<?php echo $this->render('/_include/transfer', [
    'project' => $project,
    'action' => ['invest/transfer']
]) ?>