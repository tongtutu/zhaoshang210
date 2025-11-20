<?php
use yii\helpers\Html;
$this->title = '基本设置';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['config/index']];
?>
<?php echo Html::beginForm(['config/update'], 'post', ['enctype' => 'multipart/form-data']) ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active" href="#a1" data-toggle="tab">基本信息</a></li>
                    <li class="nav-item"><a class="nav-link" href="#a2" data-toggle="tab">SEO优化</a></li>
                    <li class="nav-item"><a class="nav-link" href="#a3" data-toggle="tab">附件配置</a></li>
                    <li class="nav-item"><a class="nav-link" href="#a4" data-toggle="tab">邮件配置</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="a1">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>网站名称</label>
                                    <?php echo Html::textInput('config[site_name]', $config['site_name'], ['class' => 'form-control']) ?>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group">
                                    <label>网站域名</label>
                                    <?php echo Html::textInput('config[site_domain]', $config['site_domain'], ['class' => 'form-control']) ?>
                                </div>
                                <div class="form-group">
                                    <label>管理员邮箱</label>
                                    <?php echo Html::textInput('config[admin_email]', $config['admin_email'], ['class' => 'form-control']) ?>
                                </div>
                                <div class="form-group">
                                    <label>网站备案号</label>
                                    <?php echo Html::textInput('config[miibeian]', $config['miibeian'], ['class' => 'form-control']) ?>
                                </div>
                                <div class="form-group">
                                    <label>版权信息</label>
                                    <?php echo Html::textInput('config[copyright]', $config['copyright'], ['class' => 'form-control']) ?>
                                </div>
                                <div class="form-group">
                                    <label>网站第三方统计代码</label>
                                    <?php echo Html::textArea('config[stats_code]', $config['stats_code'], ['rows' => 10, 'cols' => 50, 'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="a2">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>标题</label>
                                    <?php echo Html::textInput('config[seo_title]', $config['seo_title'], ['class' => 'form-control']) ?>
                                </div>
                                <div class="form-group">
                                    <label>关键字</label>
                                    <?php echo Html::textInput('config[seo_keywords]', $config['seo_keywords'], ['class' => 'form-control']) ?>
                                </div>
                                <div class="form-group">
                                    <label>描 述</label>
                                    <?php echo Html::textArea('config[seo_description]', $config['seo_description'], ['rows' => 10, 'cols' => 50, 'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="a3">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>允许上传大小</label>
                                            <div class="input-group">
                                                <?php echo Html::textInput('config[upload_max_size]', $config['upload_max_size'], ['size' => 40, 'class' => 'form-control']) ?><span
                                                    class="input-group-addon">KB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>允许附件类型</label>
                                            <?php echo Html::textInput('config[upload_allow_ext]', $config['upload_allow_ext'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>附件目录</label>
                                            <?php echo Html::textInput('config[upload_root]', $config['upload_root'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>目录及文件命名规则</label>
                                            <?php echo Html::dropDownList('config[upload_rule]', $config['upload_rule'], [
                                                'Ymd' => '年/月/日/随机文件名',
                                                'Ym' => '年/月/随机文件名',
                                                'Y' => '年/随机文件名',
                                                'md5' => 'md5_file散列生成',
                                                'sha1' => 'sha1_file散列生成',
                                            ], ['class' => 'form-control  custom-select']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>水印开关</label>
                                            <?php echo Html::dropDownList('config[upload_water]', $config['upload_water'], ['open' => '开', 'close' => '关'], ['class' => 'form-control  custom-select']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-9  ">
                                        <div class="form-group">
                                            <label>水印文件</label>
                                            <?php echo Html::textInput('config[upload_water_file]', $config['upload_water_file'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>缩略图开关</label>
                                            <?php echo Html::dropDownList('config[upload_thumb]', $config['upload_thumb'], ['open' => '开', 'close' => '关'], ['class' => 'form-control  custom-select']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-9  ">
                                        <div class="form-group">
                                            <label>缩略图尺寸</label>
                                            <?php echo Html::textInput('config[upload_thumb_size]', $config['upload_thumb_size'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>水印位置</label>
                                            <?php echo Html::dropDownList('config[upload_water_pos]', $config['upload_water_pos'], ['1' => '左上角', '2' => '上居中', '3' => '右上角', '4' => '左居中', '5' => '居中', '6' => '右居中', '7' => '左下角', '8' => '下居中', '9' => '右下角'], ['class' => 'form-control custom-select']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>水印透明度</label>
                                            <div class="input-group">
                                                <?php echo Html::textInput('config[upload_water_alpha]', $config['upload_water_alpha'], ['size' => 40, 'class' => 'form-control custom-select']) ?><span
                                                    class="input-group-addon">%</span>
                                            </div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>保存图片质量</label>
                                            <div class="input-group">
                                                <?php echo Html::textInput('config[upload_quality]', $config['upload_quality'], ['class' => 'form-control']) ?><span
                                                    class="input-group-addon">%</span>
                                            </div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="callout callout-info">
                                    <li>md5和sha1规则会自动以散列值的前两个字符作为子目录，后面的散列值作为文件名
                                        <p>文件名类似于：/home/www/upload/72/ef580909368d824e899f77c7c98388.jpg</p>
                                    </li>
                                    <li>随机文件名使用的是GUID，长度为36个字符</li>
                                    <li>保存图片质量在开启缩略图或打开水印开关时才会影响到体积和质量</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="a4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SMTP 服务器地址</label>
                                            <?php echo Html::textInput('config[smtp_server]', $config['smtp_server'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> SMTP 服务器端口</label>
                                            <?php echo Html::textInput('config[smtp_port]', $config['smtp_port'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>发信人邮件地址</label>
                                            <?php echo Html::textInput('config[smtp_sender]', $config['smtp_sender'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>使用编码</label>
                                            <?php echo Html::dropDownList('config[smtp_charset]', $config['smtp_charset'], ['gbk' => 'GBK', 'utf8' => 'UTF8'], ['class' => 'form-control custom-select']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>发信人名称</label>
                                            <?php echo Html::textInput('config[smtp_sender_name]', $config['smtp_sender_name'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>是否需要验证</label>
                                            <?php echo Html::dropDownList('config[smtp_auth]', $config['smtp_auth'], ['1' => '是', '2' => '否'], ['class' => 'form-control custom-select']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SMTP 身份验证用户名</label>
                                            <?php echo Html::textInput('config[smtp_auth_user]', $config['smtp_auth_user'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SMTP 身份验证密码</label>
                                            <?php echo Html::textInput('config[smtp_auth_pass]', $config['smtp_auth_pass'], ['size' => 40, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo Html::button('<i class="fa fa-send-o"></i>提交', ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
                        <?php echo Html::resetButton('重置', ['class' => 'btn btn-danger']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo Html::endForm() ?>