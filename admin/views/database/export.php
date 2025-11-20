<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '数据库';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '备份'];
?>
<?php echo Html::beginForm(['database/do-export'], 'POST', ['enctype' => 'multipart/form-data']) ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><a class="btn btn-default btn-sm " href="<?php echo Url::to(['query']) ?>"> <i
                            class="fa fa-send-o"></i>执行SQL</a> <a class="btn btn-primary btn-sm"
                        href="<?php echo Url::to(['export']) ?>"> <i class="fa fa-floppy-o"></i>备份</a> <a
                        class="btn btn-default btn-sm" href="<?php echo Url::to(['import']) ?>"> <i
                            class="fa fa-refresh"></i>恢复</a> <a class="btn btn-sm "
                        href="<?php echo Url::to(['index']) ?>"> <i class="fa fa-history"></i>回首页</a></div>
                <div class="card-tools"></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>分卷大小</label>
                                    <div class="input-group">
                                        <?php echo Html::textInput('sizelimit', '2048', ['class' => 'form-control']) ?>
                                        <span class="input-group-addon">KB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>建表语句格式</label>
                            <div class="radio">
                                <?php echo Html::radioList('sqlcompat', '', ['' => '默认', 'MYSQL40' => 'MySQL 3.23/4.0.x ', 'MYSQL41' => 'MySQL 4.1.x/5.x'], ['class' => '']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>强制字符集</label>
                            <div class="radio">
                                <?php echo Html::radioList('sqlcharset', '', ['' => '默认', 'latin1' => 'LATIN1', 'utf8' => 'UTF-8'], ['class' => '']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="callout callout-info">
                            <ul class="tips-body break-word">
                                <li>分卷大小尽量不要超过2048KB，否则有可能导致恢复失败[1M=1024KB]</li>
                                <li>建表语句和强制字符集没特殊情况请保持默认</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row"> <?php echo Html::hiddenInput('dosubmit', 1) ?>
                    <div class="col-md-12">
                        <?php echo Html::submitButton('<i class="fa fa-send-o"></i>提交 ', ['class' => 'btn btn-primary']) ?>
                        <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']) ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo Html::endForm() ?>