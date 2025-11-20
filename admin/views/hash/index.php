<?php
use bagesoft\helpers\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$request = Yii::$app->request;
$timestamp = time();
$this->title = '校验码';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get'); ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">状态</span>
                                </div>
                                <?php echo Html::dropdownList('module', $request->get('state'), ['' => '--', '1' => '未使用', '2' => '已使用'], ['class' => 'form-control form-control-sm  ']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">目标</span>
                                </div>
                                <?php echo Html::textInput('target', $request->get('target'), ['class' => 'form-control form-control-sm']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">校验码</span>
                                </div>
                                <?php echo Html::textInput('hash', $request->get('hash'), ['class' => 'form-control form-control-sm']); ?>
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
                                            <th>TOKEN</th>
                                            <th>HASH</th>
                                            <th>时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <tr>
                                                    <td scope="row"> <?php echo $row->id; ?> </td>
                                                    <td><?php echo Html::encode($row->token); ?></td>
                                                    <td>
                                                        <p> <?php echo Html::encode($row->hash); ?> </p>
                                                        <p>
                                                            <?php if ($row->state == 1): ?>
                                                                <span class="label label-default">未使用</span>
                                                            <?php elseif ($row->state == 2): ?>
                                                                <span class="label label-success">已使用</span>
                                                            <?php elseif ($row->state == 3): ?>
                                                                <span class="label label-danger">已过期</span>
                                                            <?php endif; ?>
                                                            <?php if ($timestamp > $row->expire_at && $row->state == 1): ?>
                                                                <span class="label label-danger">已过期</span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>创建： <?php echo date('Y-m-d H:i:s', $row->created_at); ?> </p>
                                                        <?php if ($row->state == 1): ?>
                                                            <p>过期：
                                                                <?php if ($timestamp > $row->expire_at): ?>
                                                                    <?php echo date('Y-m-d H:i:s', $row->expire_at); ?>
                                                                <?php else: ?>
                                                                    <?php echo Utils::timeFmt($row->expire_at); ?>
                                                                <?php endif; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if ($row->state == 2): ?>
                                                            <p>使用： <?php echo date('Y-m-d H:i:s', $row->used_at); ?> </p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <!-- <div class="btn-group"> <a type="button"
                                                                class="btn btn-default btn-sm vframe"
                                                                data-url="<?php echo Url::to(['item', 'id' => $row->id, 'size' => 'mini']); ?>"
                                                                target="_blank">详情</a> </div> -->
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <td colspan="5" class="text-center text-primary">暂无记录</td>
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