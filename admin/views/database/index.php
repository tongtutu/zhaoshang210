<?php
use bagesoft\helpers\Utils;
use yii\helpers\Url;
$this->title = '数据库';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '数据表'];
$this->registerJs('
$(function() {
    $(".opt-btn").click(function() {
        var command = $(this).attr("command");
        $.post(this.rel, $("#list-form").serializeArray(),
        function(res) {
            if (res.code == 200) {
                $("#sql-result").html(res.data.result);
                $("#sql-window").modal();
            } else {
                alert(res.message);
                return false;
            }
        })
    });
});
', \yii\web\View::POS_END);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card ">
            <div class="card-header">
                <div class="card-title"><a class="btn btn-primary btn-sm " href="<?php echo Url::to(['query']) ?>"> <i
                            class="fa fa-send-o"></i>执行SQL</a> <a class="btn btn-default btn-sm"
                        href="<?php echo Url::to(['export']) ?>"><i class="fa fa-floppy-o"></i>备份</a> <a
                        class="btn btn-default btn-sm " href="<?php echo Url::to(['import']) ?>"><i
                            class="fa fa-refresh"></i>恢复</a></div>
                <div class="card-tools"> 当前数据库：<strong><span class="label label-default">
                            <?php echo Utils::byteFmt($dataSize) ?></span></strong> </div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-12">
                        <form id="list-form" name="list-form" method="POST">
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover  ">
                                    <thead>
                                        <tr>
                                            <th>表名称</th>
                                            <th>类型</th>
                                            <th>字符集</th>
                                            <th>记录数</th>
                                            <th>大小</th>
                                            <th>碎片</th>
                                            <th>注释</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tables as $key => $row): ?>
                                        <tr>
                                            <td>
                                                <div class="icheck-primary">
                                                    <input type="checkbox" value="<?php echo $row['Name'] ?>"
                                                        id="<?php echo $row['Name'] ?>" name="table[]">
                                                    <label for="<?php echo $row['Name'] ?>">
                                                        <?php echo $row['Name'] ?></label>
                                                </div>
                                            </td>
                                            <td><?php echo $row['Engine'] ?></td>
                                            <td><?php echo $row['Collation'] ?></td>
                                            <td><?php echo $row['Rows'] ?></td>
                                            <td><?php echo Utils::byteFmt($row['Data_length']) ?></td>
                                            <td><?php echo Utils::byteFmt($row['Data_free']) ?></td>
                                            <td><?php echo $row['Comment'] ?></td>
                                        </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <div class="mailbox-controls">
                    <div class="float-left">
                        <button type="button" class="btn btn-sm checkbox-toggle"><i class="far fa-square"></i>
                            选择</button>
                        <a class="btn btn-default btn-sm opt-btn"
                            rel="<?php echo Url::to(['do-query', 'command' => 'optimze']) ?>" command="optimze"> <i
                                class="fa fa-send-o"></i>优化</a> <a class="btn btn-default btn-sm opt-btn"
                            rel="<?php echo Url::to(['do-query', 'command' => 'check']) ?>" command="check"> <i
                                class="fa fa-hourglass-3"></i>检查</a> <a class="btn btn-default btn-sm opt-btn"
                            rel="<?php echo Url::to(['do-query', 'command' => 'analyze']) ?>" command="analyze"><i
                                class="fa fa-print"></i>分析</a> <a class="btn btn-default btn-sm opt-btn"
                            rel="<?php echo Url::to(['do-query', 'command' => 'repair']) ?>" command="repair"> <i
                                class="fa fa-wrench"></i>修复</a> <a class="btn btn-default btn-sm opt-btn"
                            rel="<?php echo Url::to(['do-query', 'command' => 'columns']) ?>" command="columns"> <i
                                class="fa fa-info"></i>表结构</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="sql-window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">执行结果</h4>
                    </div>
                    <div class="modal-body" id="sql-result"> ... </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>