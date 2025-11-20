<?php
use bagesoft\helpers\Utils;
use yii\helpers\Url;
$this->title = '数据库';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '恢复'];
$this->registerJs('
$(function() {
    $(".opt-btn").click(function() {
        $.post(this.rel, $("#list-form").serializeArray(),
        function(result) {
            alert(result.message);
            if (result.code == 200) {
                window.location.reload();
            } else {
                return false;
            }
        })
    });
});
', \yii\web\View::POS_END);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><a class="btn btn-default btn-sm" href="<?php echo Url::to(['query']) ?>"> <i
                            class="fa fa-send-o"></i>执行SQL</a> <a class="btn btn-default btn-sm "
                        href="<?php echo Url::to(['export']) ?>"><i class="fa fa-floppy-o"></i>备份</a> <a
                        class="btn btn-primary btn-sm " href="<?php echo Url::to(['import']) ?>"><i
                            class="fa fa-refresh"></i>恢复</a><a class="btn btn-sm"
                        href="<?php echo Url::to(['index']) ?>"> <i class="fa fa-history"></i>回首页</a></div>
                <div class="card-tools"><small>尺寸：</small><span class="label label-default">
                        <?php echo Utils::byteFmt($filesize) ?></span></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive no-padding mailbox-messages">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>分卷</th>
                                            <th>名称</th>
                                            <th>大小</th>
                                            <th>时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($filelist as $key => $file): ?>
                                        <tr style="background-color: <?php echo $file['bgcolor'] ?>">
                                            <td>
                                                <div class="icheck-primary">
                                                    <input type="checkbox" value="<?php echo $file['filename'] ?>"
                                                        id="<?php echo $file['filename'] ?>" name="files[]">
                                                    <label for="<?php echo $file['filename'] ?>">
                                                        <?php echo $file['number'] ?></label>
                                                </div>
                                            </td>
                                            <td><label for="<?php echo $file['filename'] ?>">
                                                    <?php echo str_replace('bagecms_', '', $file['filename']) ?></label>
                                            </td>
                                            <td><?php echo Utils::byteFmt($file['filesize']) ?></td>
                                            <td><?php echo $file['maketime'] ?></td>
                                            <td>
                                                <div class="btn-group"> <a type="button" class="btn btn-sm btn-default"
                                                        href="<?php echo Url::to(['import', 'pre' => $file['pre'], 'dosubmit' => '1']) ?>">恢复</a>
                                                    <a type="button"
                                                        class="btn btn-sm btn-default dropdown-toggle  dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span></a>
                                                    <div class="dropdown-menu " role="menu"> <a class="dropdown-item"
                                                            href="<?php echo Url::to(['download', 'file' => $file['filename']]) ?>">下载</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <div class="callout callout-info">
                            <ul class="tips-body break-word">
                                <li>数据库恢复过程中请不要中断，否则可能会导致数据不完整或无法进入后台</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm checkbox-toggle"><i class="far fa-square"></i>
                            选择</button>
                        <div class="btn-group"> <a class="btn btn-default btn-sm opt-btn"
                                rel="<?php echo Url::to(['delete']) ?>" command="optimze"> <i
                                    class="fa fa-recycle"></i>删除 </a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>