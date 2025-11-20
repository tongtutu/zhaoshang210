<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '推送模板';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card ">
            <div class="card-header">
                <div class="card-title"><a class="btn btn-primary" href="<?php echo Url::to(['create']) ?>"> <i
                            class="fa fa-file-o"></i> 录入 </a></div>
                <div class="card-tools">
                    <?php if ($count > 0): ?>
                    共 <kbd> <?php echo $count ?></kbd> 条记录
                    <?php endif?>
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
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <th scope="row"><?php echo $row->id ?></th>
                                            <td><?php echo Html::encode($row->title) ?>
                                                <p><span
                                                        class="label label-default"><?php echo Html::encode($row->title_alias) ?></span>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-width-sm"> <a type="button"
                                                        class="btn btn-default btn-sm"
                                                        href="<?php echo Url::to(['update', 'id' => $row->id]) ?>">编辑</a>
                                                    <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo Url::to(['delete', 'id' => $row->id]) ?>">删除</a>
                                                        </li>
                                                    </ul>
                                                </div>



                                            </td>
                                        </tr>
                                        <?php endforeach?>
                                        <?php else: ?>
                                        <?php $opt = ' style="display:none"'?>
                                        <td colspan="3" class="text-center text-primary">暂无记录</td>
                                        <?php endif?>
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