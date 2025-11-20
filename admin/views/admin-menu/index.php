<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '后台栏目';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
$request = Yii::$app->request;
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
                    <?php endif;?>
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
                                            <th>名称</th>
                                            <th>状态</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <th scope="row">
                                                <div class="icheck-primary">
                                                    <input type="checkbox" value="<?php echo $row->id; ?>"
                                                        id="check-<?php echo $row->id; ?>" name="id[]">
                                                    <label for="check-<?php echo $row->id; ?>">
                                                        <?php echo $row->id; ?></label>
                                                </div>
                                            </th>
                                            <td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row->depath); ?><?php echo Html::encode($row->title); ?>
                                                &nbsp;<small
                                                    class="label label-default"><?php echo Html::encode($row->route); ?></small>
                                            </td>
                                            <td><?php if ($row->state == 2): ?>
                                                <i class="fa fa-eye-slash text-danger" title="隐藏"></i>
                                                <?php elseif ($row->state == 1): ?>
                                                <i class="fa fa-eye" title="显示"></i>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <div class="btn-group"><a
                                                        href="<?php echo Url::to(['move', 'type' => 'up', 'id' => $row->id]); ?>"
                                                        class="btn btn-default btn-sm"><i class="fas fa-angle-up"></i>
                                                        上移</a> <a
                                                        href="<?php echo Url::to(['move', 'type' => 'down', 'id' => $row->id]); ?>"
                                                        class="btn btn-default btn-sm"><i class="fas fa-angle-down">
                                                            下移</i></a> <a type="button" class="btn btn-sm btn-default"
                                                        href="<?php echo Url::to(['update', 'id' => $row->id]); ?>">编辑</a>
                                                    <a type="button"
                                                        class="btn btn-sm btn-default dropdown-toggle  dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span></a>
                                                    <!-- <div class="dropdown-menu dropdown-menu-right" role="menu"> <a
                                                            class="dropdown-item"
                                                            href="<?php echo Url::to(['delete', 'id' => $row->id]); ?>">删除</a>
                                                    </div> -->
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <?php $opt = ' style="display:none"';?>
                                        <td colspan="4" class="text-center text-primary">暂无记录</td>
                                        <?php endif;?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer p-0">
                <div class="mailbox-controls" <?php echo $opt; ?>>
                    <button type="button" class="btn  btn-sm checkbox-toggle"><i class="far fa-square"></i> 选择</button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm"><i class="far fa-eye"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fas fa-eye-slash"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
                <div class="mailbox-controls"> </div>
            </div>
        </div>
    </div>
</div>