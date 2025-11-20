<?php
use bagesoft\helpers\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '会员';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
$request = Yii::$app->request;
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
                    <div class="col-lg-12">
                        <div class="collapse show" id="searchCollapse">
                            <div class="card">
                                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']) ?>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-4">

                                            <?php echo Html::textInput('title', $request->get('title'), ['class' => 'form-control form-control-sm ', 'placeholder' => '用户名']) ?>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-4">

                                            <?php echo Html::textInput('email', $request->get('email'), ['class' => 'form-control form-control-sm ', 'placeholder' => 'email']) ?>
                                        </div>
                                        <div class="col-lg-3 col-md-2 col-sm-4">

                                            <?php echo Html::dropDownList('state', $request->get('state'), ['' => '--', '1' => '显示', 2 => '隐藏'], ['class' => 'form-control input-sm custom-select' ]) ?>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-4">
                                            <?php echo Html::hiddenInput('opt', 'search') ?>
                                            <button type="submit" class="btn btn-default btn-sm"><i
                                                    class="fa fa-search"></i>提交</button>
                                            <a href="<?php echo Url::to(['index']) ?>" class="btn btn-xs btn-link"> <i
                                                    class="fa fa-undo"></i>取消</a>
                                        </div>
                                    </div>
                                    <?php echo Html::endForm() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover  ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>用户名</th>
                                            <th>用户组</th>
                                            <th>注册/更新</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                        <?php foreach ($datalist as $key => $row): ?>
                                        <tr>
                                            <th scope="row"> <?php echo $row->id ?> </th>
                                            <td><?php echo Html::encode($row->username) ?></td>
                                            <td><?php echo $row->group->group_name ?></td>
                                            <td>
                                                <p> <?php echo date('Y-m-d H:i', $row->created_at) ?> </p>
                                                <?php if ($row->updated_at > 0): ?>
                                                <p> <?php echo Utils::timeFmt($row->updated_at) ?> </p>
                                                <?php endif?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-width-sm"> <a type="button"
                                                        class="btn btn-default btn-sm"
                                                        href="<?php echo Url::to(['update', 'id' => $row->id]) ?>">编辑</a>
                                                    <a type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a
                                                                href="<?php echo Url::to(['delete', 'id' => $row->id]) ?>">删除</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach?>
                                        <?php else: ?>
                                        <?php $opt = ' style="display:none"'?>
                                        <td colspan="5" class="text-center text-primary">暂无记录</td>
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