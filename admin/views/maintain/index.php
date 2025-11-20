<?php

//use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use bagesoft\functions\TagsFunc;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\constant\ProjectConst;
use kartik\daterange\DateRangePicker;

$this->title = '跟进维护';
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
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">项目名称</span>
                                </div>
                                <?php echo Html::textInput('projectName', $request->get('projectName'), ['class' => 'form-control form-control-sm', 'placeholder' => '', 'id' => 'projectName']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">跟进人</span>
                                </div>
                                <?php echo Html::textInput('partnerName', $request->get('partnerName'), ['class' => 'form-control form-control-sm', 'placeholder' => '', 'id' => 'partnerName']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">跟进类型</span>
                                </div>
                                <?php echo Html::dropDownList('typeid', $request->get('typeid'), ProjectConst::MAINTAIN_TYPE, ['class' => 'form-control  form-control-sm custom-select', 'prompt' => '-']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">审核结果</span>
                                </div>
                                <?php echo Html::dropDownList('state', $request->get('state'), ProjectConst::MAINTAIN_STATUS, ['class' => 'form-control form-control-sm custom-select', 'prompt' => '-']); ?>
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
                        <div class="col-lg-3 col-md-2 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">提交时间</span>
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

                        <div class="col-lg-2 col-md-3 col-sm-3"> <?php echo Html::hiddenInput('opt', 'search'); ?>
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
            </div>
            <?php endif; ?>

            <div class="card-body p-0">
                <div class="row">

                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>项目名称</th>
                                            <th>创建人</th>
                                            <th>跟进人</th>
                                            <th>跟进类型</th>
                                            <th>项目阶段</th>
                                            <th>状态</th>
                                            <th>附件</th>
                                            <th>提交时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <?php $attachFiles = UploadFunc::getlist(System::UPLOAD_SOURCE_MAINTAIN, $row['id']); ?>
                                        <tr>
                                            <td> <?php echo $row['id']; ?> </td>
                                            <td><kbd><?php echo System::SOURCE[$row['source']]; ?></kbd>
                                                <a
                                                    href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"><?php echo Html::encode($row['project_name']); ?></a>
                                            </td>
                                            <td> <?php echo Html::encode($row['partner_name']); ?> </td>
                                            <td> <?php echo Html::encode($row['username']); ?> </td>

                                            <td><?php echo ProjectConst::MAINTAIN_TYPE[$row['typeid']]; ?> </td>
                                            <td><?php echo ProjectConst::STEPS[$row['state']]; ?> </td>
                                            <td><?php echo ProjectConst::MAINTAIN_STATUS[$row['state']]; ?></td>
                                            <td> <?php if ($attachFiles): ?>
                                                <div class="btn-group "> <a type="button"
                                                        class="btn btn-default btn-sm">附件=></a>
                                                    <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <?php foreach ($attachFiles as $key => $file): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                                target="_blank"><i class="fa fa-paperclip"></i>
                                                                <?php echo Html::encode($file->real_name); ?>

                                                            </a>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('Y-m-d H:i', $row['created_at']); ?>
                                            </td>
                                            <td> <a class="btn btn-sm btn-default"
                                                    href="<?php echo Url::to(['item', 'id' => $row['id']]); ?>"
                                                    role="button">详情</a>

                                            </td>
                                        </tr>

                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <?php $opt = ' style="display:none"'; ?>
                                        <td colspan="9" class="text-center text-primary">暂无记录</td>
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