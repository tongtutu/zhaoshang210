<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$request = Yii::$app->request;
$timestamp = time();
$this->title = '校验码限定';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">

                            <?php echo Html::dropdownList('typed', $request->get('typed'), ['' => '--', '1' => '永久限制', '2' => '临时限制'], ['class' => 'form-control form-control-sm', 'id' => 'typed']); ?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">

                            <?php echo Html::textInput('target', $request->get('target'), ['class' => 'form-control form-control-sm']); ?>
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
                <div class="card-title"><a class="btn btn-primary  btn-sm"
                        href="<?php echo Url::to(['create']); ?>"> 录入 </a> <a class="btn btn-default btn-sm"
                        href="<?php echo Url::to(['hash/index']); ?>"> 返回校验码 </a> </div>
                <div class="card-tools">
                    <?php if ($count > 0): ?>
                    共 <kbd> <?php echo $count; ?></kbd> 条记录
                    <?php endif;?>
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
                                            <th>目标</th>
                                            <th>类型</th>
                                            <th>时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <th scope="row"> <?php echo $row->id; ?> </th>
                                            <td><?php echo Html::encode($row->target); ?></td>
                                            <td>
                                                <p>
                                                    <?php if ($row->typed == 1): ?>
                                                    <span class="label label-danger">永久限制</span>
                                                    <?php else: ?>
                                                    <span class="label label-default">临时限制</span>
                                                    <?php endif;?>
                                                </p>
                                            </td>
                                            <td>
                                                <p>创建： <?php echo date('Y-m-d H:i:s', $row->created_at); ?> </p>
                                                <p>解除：
                                                    <?php if ($row->typed == 2): ?>
                                                    <?php echo date('Y-m-d H:i:s', $row->expired_at); ?>
                                                    <?php else: ?>
                                                    -
                                                    <?php endif;?>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="btn-group"> <a type="button" class="btn btn-sm btn-default"
                                                        href="<?php echo Url::to(['update', 'id' => $row->id]); ?>">编辑</a>
                                                    <a type="button"
                                                        class="btn btn-sm btn-default dropdown-toggle  dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span></a>
                                                    <div class="dropdown-menu dropdown-menu-right" role="menu"> <a
                                                            class="dropdown-item"
                                                            href="<?php echo Url::to(['delete', 'id' => $row->id]); ?>">删除</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <td colspan="5" class="text-center text-primary">暂无记录</td>
                                        <?php endif;?>
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