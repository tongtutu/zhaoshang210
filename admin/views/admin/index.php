<?php

use bagesoft\constant\UserConst;
use bagesoft\helpers\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '管理员';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card ">
            <div class="card-header">
                <div class="card-title"><a class="btn btn-primary" href="<?php echo Url::to(['create']); ?>"> <i
                            class="fa fa-file-o"></i> 录入 </a></div>
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
                                            <th>最后登录</th>
                                            <th>状态</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <tr>
                                                    <td><?php echo $row->id; ?></td>
                                                    <td><?php echo Html::encode($row->username); ?><br><code><?php echo Html::encode($row->mobile); ?></code>
                                                    </td>
                                                    <td><span <?php if ($row->gid == 2): ?> class="label label-danger" <?php endif; ?>> <?php echo $row->group->group_name; ?> </span></td>
                                                    <td><?php if ($row->last_login_at > 0 && $row->id > 1): ?>
                                                            <?php echo Utils::timeFmt($row->last_login_at); ?>

                                                            <p> <?php echo Html::encode($row->last_login_ip); ?></p><?php endif; ?>
                                                    </td>
                                                    <td><span <?php if ($row->state == 2): ?> class="label label-danger" <?php endif; ?>><?php echo UserConst::STATUS[$row->state]; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($row->id > 1): ?>
                                                            <div class="btn-group"> <a type="button" class="btn btn-sm btn-default"
                                                                    href="<?php echo Url::to(['update', 'id' => $row->id]); ?>">编辑</a>
                                                                <a type="button"
                                                                    class="btn btn-sm btn-default dropdown-toggle  dropdown-icon"
                                                                    data-toggle="dropdown">
                                                                    <span class="sr-only">Toggle Dropdown</span></a>
                                                                <div class="dropdown-menu dropdown-menu-right" role="menu"> <a
                                                                        class="dropdown-item"
                                                                        href="<?php echo Url::to(['admin-log/index', 'uid' => $row->id, 'opt' => 'search']); ?>"
                                                                        target="_blank">日志</a>

                                                                    <a class="dropdown-item"
                                                                        href="<?php echo Url::to(['delete', 'id' => $row->id]); ?>">删除</a>

                                                                </div>
                                                            </div> <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php $opt = ' style="display:none"'; ?>
                                            <td colspan="6" class="text-center text-primary">暂无记录</td>
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