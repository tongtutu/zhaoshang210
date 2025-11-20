<?php
use yii\helpers\Url;
$this->title = '缓存管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->registerJs('
    $(function() {
      $("#flush_cache").click(function() {
        $("#flush_cache").html("请稍等，正在执行中...");
        $.post("' . Url::to(["cache/flush-cache"]) . '", {},
        function(result) {
            if(result.code == 200){
                $("#flush_cache").html(result.message).addClass("disabled");
            }else{
                alert(result.message);
            }
        },
        "json");
      });
      $("#clear_assets").click(function() {
        $("#clear_assets").html("请稍等，正在执行中...");
        $.post("' . Url::to(["cache/clear-assets"]) . '", {},
        function(result) {
            if(result.code == 200){
                $("#clear_assets").html(result.message).addClass("disabled");
            }else{
                alert(result.message);
            }
        },
        "json");
      });
});
', \yii\web\View::POS_END);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel ">
                            <div class="panel-heading">
                                <h3 class="panel-title">系统缓存</h3>
                            </div>
                            <div class="panel-body"> <a class="btn btn-default" id="flush_cache"><i
                                        class="glyphicon glyphicon-flash"></i> 清空缓存</a> </div>
                        </div>
                        <div class="panel ">
                            <div class="panel-heading">
                                <h3 class="panel-title">前端资源</h3>
                            </div>
                            <div class="panel-body"> <a id="clear_assets" class="btn btn-default"><i
                                        class="glyphicon glyphicon-trash"></i> 清空缓存</a> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="callout callout-info">
                            <ul class="tips-body break-word">
                                <li>系统注册的css、js等静态资源有修改或更新后建议删除缓存</li>
                                <li>缓存被清空后下次运行时会全新生成</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>