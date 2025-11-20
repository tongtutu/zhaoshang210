<?php

use bagesoft\functions\AdminFunc;
use bagesoft\helpers\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '操作日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
$request = Yii::$app->request;
$ipRegion = new \bagesoft\library\IpRegion();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">类型</span>
                                </div>
                                <?php echo Html::dropdownList('method', $request->get('method'), ['' => '--', 'GET' => 'GET', 'POST' => 'POST'], ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">用户</span>
                                </div>
                                <?php echo Html::textInput('username', $request->get('username'), ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">用户UID</span>
                                </div>
                                <?php echo Html::textInput('uid', $request->get('uid'), ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">IP</span>
                                </div>
                                <?php echo Html::textInput('ip', $request->get('ip'), ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4"> <?php echo Html::hiddenInput('opt', 'search'); ?>
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
                        共 <kbd><?php echo $count; ?></kbd> 条记录
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
                                            <th>行为</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <tr>
                                                    <th scope="row"> <?php echo $row->id; ?> </th>
                                                    <td>
                                                        <p class="break-word">
                                                            <code><?php echo Html::encode(AdminFunc::transName($row->action)); ?></code>
                                                            <?php echo Html::encode($row->action_url); ?>
                                                            <?php if ($row->datas): ?>
                                                                <!-- <a type="button" class="btn btn-sm vframe"
                                                                    data-url="<?php echo Url::to(['item', 'id' => $row->id, 'size' => 'mini']); ?>"><i
                                                                        class="fa fa-search"></i>详情</a> -->
                                                            <?php endif; ?>
                                                        </p>
                                                        <p><small> <?php echo Html::encode($row->user_agent); ?></small></p>
                                                        <p><span class="label label-default">
                                                                <?php echo Html::encode($row->username); ?></span>&nbsp;&nbsp;
                                                            <?php echo Html::encode($row->ip); ?>&nbsp;&nbsp;
                                                            <?php echo date('Y-m-d H:i:s', $row->created_at); ?> &nbsp;&nbsp;
                                                            <?php echo Utils::timeFmt($row->created_at); ?>
                                                        </p>
                                                        <?php if ($row->intro): ?>
                                                            <span class="label label-default">
                                                                <?php echo Html::encode($row->intro); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <td colspan="2" class="text-center text-primary">暂无记录</td>
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