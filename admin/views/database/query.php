<?php
use yii\helpers\Url;
$this->title = '数据库';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '执行SQL'];
$this->registerJs('
$(function() {
    $(".opt-btn").click(function() {
        var command = $("#command").val();
        $.post("' . Url::to(["execute "]) . '", {
            command: command
        },
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
    <div class="card-title"><a class="btn btn-primary btn-sm " href="<?php echo Url::to(['query']) ?>"><i class="fa fa-send-o"></i>执行SQL</a> <a class="btn btn-default btn-sm " href="<?php echo Url::to(['export']) ?>"> <i class="fa fa-floppy-o"></i>备份</a> <a class="btn btn-default btn-sm " href="<?php echo Url::to(['import']) ?>"> <i class="fa fa-refresh"></i>恢复</a> <a class="btn btn-sm " href="<?php echo Url::to(['index']) ?>"> <i class="fa fa-history"></i>回首页</a></div>
    <div class="card-tools"> </div>
  </div>
  <div class="card-body ">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-8">
            <fieldset<?php if (Yii::$app->params['allowDbExe'] !== 1): ?> disabled
                            <?php endif?>>
              <form>
                <div class="panel">
                  <div class="panel-heading"> <strong><i class="fa fa-file-text"></i> 输入SQL</strong> </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <textarea class="form-control" rows="5" placeholder="<?php if (Yii::$app->params['allowDbExe'] !== 1): ?> 该功能需要在配置文件 config/params.php 中手动修改 allowDbExe=1 <?php endif?>" id="command"></textarea>
                    </div>
                    <a class="btn btn-default opt-btn"><i class="fa fa-send-o"></i>提交执行</a> </div>
                </div>
              </form>
            </fieldset>
          </div>
          <div class="col-md-4">
            <div class="callout callout-info">
                <ul class="tips-body break-word">
                  <li>每行一条SQL语句且用分号(;) 结尾，查询结果数据较多请使用 limit 限定，否则很慢</li>
                  <li>该功能需要在配置文件中手动开启，开启方法：app/config/params.php 中手动修改 allowDbExe=1</li>
                  <li class="text-danger">操作不当或被恶意利用会对数据库进行不可逆破坏，不建议为非超级管理员组开放权限</li>
                </ul>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="sql-window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
