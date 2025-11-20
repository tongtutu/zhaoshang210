<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '文件存储';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '列表'];
$request = Yii::$app->request;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="collapse show" id="searchCollapse">
            <div class="card">
                <div class="card-body"> <?php echo Html::beginForm(['index'], 'get', ['class' => '']); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-4 ">
                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">图片名称</span>
                                </div>
                                <?php echo Html::textInput('realName', $request->get('realName'), ['class' => 'form-control form-control-sm', 'placeholder' => '']); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4 ">
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
                        共 <kbd> <?php echo $count; ?></kbd> 条记录
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row">

                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>原始/上传</th>
                                            <th>上传时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($datalist): ?>
                                            <?php foreach ($datalist as $key => $row): ?>
                                                <tr>
                                                    <th scope="row"> <?php echo $row->id; ?> </th>
                                                    <td><a
                                                                href="<?php echo Yii::$app->params['res.url'] . '/' . Html::encode($row->file_name); ?>"
                                                                target="_blank"><?php echo Html::encode($row->real_name); ?>
                                                        </a>
                                                        <br><?php echo Html::encode($row->file_mime); ?>
                                                    </td>
                                                    <td>
                                                        <p> <?php echo date('Y-m-d H:i', $row->created_at); ?> </p>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-width-sm"> <a type="button"
                                                                class="btn btn-default btn-sm"
                                                                href="<?php echo Url::to(['delete', 'id' => $row->id]); ?>">删除</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
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