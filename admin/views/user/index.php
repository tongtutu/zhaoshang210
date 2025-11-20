<?php

use bagesoft\constant\UserConst;
use bagesoft\helpers\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '账号管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
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
                                    <span class="input-group-text">姓名</span>
                                </div>
                                <?php echo Html::textInput('username', $request->get('username'), ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-2">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">状态</span>
                                </div>
                                <?php echo Html::dropDownList('state', $request->get('state'), ['' => '--'] + UserConst::STATUS, ['class' => 'form-control form-control-sm custom-select']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4"> <?php echo Html::hiddenInput('opt', 'search'); ?>
                            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i>提交</button>
                            <a href="<?php echo Url::to(['index']); ?>" class="btn btn-xs btn-link"> <i
                                    class="fa fa-undo"></i>取消</a>
                        </div>
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
            <div class="card-title"><a class="btn btn-primary" href="<?php echo Url::to(['create']); ?>">
                    <i class="fa fa-file-o"></i> 录入 </a></div>
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
                            <table class="table table-hover  ">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>姓名/手机</th>
                                        <th>用户组</th>
                                        <th>状态</th>
                                        <th>注册/更新</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                            <tr>
                                                <td> <?php echo $row->id; ?> </td>
                                                <td><?php echo Html::encode($row->username); ?><br><code><?php echo Html::encode($row->mobile); ?></code>
                                                    <!-- <a href="//uc.zhaoshang.s7q.cn/public/slogin?id=<?php echo $row->id ?>"
                                                        class="btn-link" target="_blank">登录员工</a> -->
                                                </td>
                                                <td><?php echo $row->group->id; ?>,<?php echo $row->group->group_name; ?></td>
                                                <td><?php echo UserConst::STATUS[$row->state]; ?></td>
                                                <td>
                                                    <?php echo date('Y-m-d H:i', $row->created_at); ?>
                                                    <?php if ($row->updated_at > 0): ?>
                                                        <br> <?php echo Utils::timeFmt($row->updated_at); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>

                                                    <div class="btn-group"> <a type="button" class="btn btn-sm btn-default"
                                                            href="<?php echo Url::to(['update', 'id' => $row->id]); ?>">编辑</a>
                                                        <a type="button"
                                                            class="btn btn-sm btn-default dropdown-toggle  dropdown-icon"
                                                            data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span></a>
                                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                        <a class="dropdown-item"
                                                            href="<?php echo Url::to(['delete', 'id' => $row->id]); ?>">删除</a>
                                                    </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php $opt = ' style="display:none"'; ?>
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