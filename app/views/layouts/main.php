<?php

use app\assets\AppAsset;
use bagesoft\models\UserMenu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
AppAsset::register($this);
$route = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
$this->registerJs("
    // 定义全局方法
    function handleFileUploaded(event, data, previewId, index, fileListSelector, attachFileSelector) {
        var fileId = data.response.fileId;
        var fileName = data.response.realName; // 使用服务器返回的真实文件名
        
        // 将文件名和删除按钮添加到 filelist
        $(fileListSelector).append(
            '<li class=\"list-group-item d-flex align-items-center\" id=\"file-' + fileId + '\">' +
                '<span class=\"file-name\"><i class=\"fa fa-paperclip\"></i>&nbsp;&nbsp;' + fileName + '</span>&nbsp;&nbsp;' +
                '<button type=\"button\" class=\"btn btn-danger btn-xs delete-file\" data-file-id=\"' + fileId + '\" data-file-list=\"' + fileListSelector + '\" data-attach-file=\"' + attachFileSelector + '\">' +
                    '<i class=\"fa fa-trash\"></i> 删除' +
                '</button>' +
            '</li>'
        );
        
        // 将文件ID添加到隐藏输入框
        var hiddenInput = $(attachFileSelector);
        var currentIds = hiddenInput.val();
        if (currentIds) {
            hiddenInput.val(currentIds + ',' + fileId);
        } else {
            hiddenInput.val(fileId);
        }
    }
");

$this->registerJs("
    $(document).on('click', '.delete-file', function(e) {
        e.preventDefault(); // 阻止默认行为
        
        var fileId = $(this).data('file-id');
        var fileListSelector = $(this).data('file-list'); // 获取文件列表选择器
        var attachFileSelector = $(this).data('attach-file'); // 获取隐藏输入框选择器
        var listItem = $('#file-' + fileId);
        
        // 请求远程接口删除文件
        $.ajax({
            url: '" . \yii\helpers\Url::to(['upload/delete-file']) . "',
            type: 'GET',
            data: { fileId: fileId },
            success: function(response) {
                if (response.state==\"success\") {
                    // 删除对应的 li 元素
                    listItem.remove();
                    
                    // 从隐藏输入框中删除文件ID
                    var hiddenInput = $(attachFileSelector);
                    var currentIds = hiddenInput.val().split(',');
                    var newIds = currentIds.filter(function(id) {
                        return id != fileId;
                    });
                    hiddenInput.val(newIds.join(','));
                } else {
                    new $.Zebra_Dialog('文件删除失败：' + response.message, {
                        modal: false
                    });
                }
            },
            error: function() {
                new $.Zebra_Dialog('请求失败，请稍后重试。', {
                    modal: false
                });
            }
        });
    });
");
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="<?php echo Yii::$app->language; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo Html::encode($this->title); ?>-APP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->head(); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed ">
    <?php $this->beginBody(); ?>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="###" role="button"><i
                            class="fas fa-bars"></i></a> </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown"> <a class="nav-link " data-toggle="dropdown" href="#"> <i
                            class="fas fa-user">
                            <?php echo Html::encode($this->context->user->username); ?>(<?php echo $this->context->user->id; ?>)</i>
                        <span class="fas fa-bars"></span> </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>

                        <div class="dropdown-divider"></div>
                        <a href="<?php echo Url::to(['public/logout']); ?>" class="dropdown-item" title="退出系统"><i
                                class="fas fa-sign-out-alt mr-2"></i> 退出系统</a> <span
                            class="dropdown-item dropdown-">上次登录：
                            <?php echo date('Y-m-d H:i:s', $this->context->user->last_login_at); ?></span>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar elevation-4 sidebar-light-lightblue">
            <a href="<?php echo Url::to(['site/index']); ?>" class="brand-link "> <img
                    src="<?php echo Yii::$app->params['res.url']; ?>/static/app/img/AdminLTELogo.png"
                    alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> <span
                    class="brand-text font-weight-light">旗联云-员工</span> </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-flat " data-widget="treeview" role="menu"
                        data-accordion="false">
                        <?php foreach (UserMenu::tree($this->context->accessList) as $key => $tree): ?>
                            <?php $isopen = in_array(Yii::$app->controller->id, $tree['access_route']) || Yii::$app->controller->id . '/' . Yii::$app->controller->action->id == $tree['route']; ?>
                            <li class="nav-item has-treeview  menu-open<?php if ($isopen): ?><?php endif; ?>"> <a
                                    href="<?php if ($tree['access_num'] == 1): ?><?php echo Url::to([$tree['route']]); ?><?php else: ?>###<?php endif; ?>"
                                    class="nav-link <?php if ($isopen): ?> active<?php endif; ?>"> <i
                                        class="nav-icon fas <?php echo $tree['icon']; ?>"></i>
                                    <p> <?php echo $tree['title']; ?>
                                        <?php if ($tree['access_num'] > 1): ?>
                                            <i class="right fas fa-angle-left"></i>
                                        <?php endif; ?>
                                    </p>
                                </a>
                                <?php if (is_array($tree['child']) && count($tree['child']) > 0): ?>
                                    <ul class="nav nav-treeview">
                                        <?php foreach ($tree['child'] as $subkey => $subTree): ?>
                                            <?php if ($subTree['onmenu'] == 1): ?>
                                                <li class="nav-item"> <a href="<?php echo Url::to([$subTree['route']]); ?>"
                                                        class="nav-link <?php if ($route == $subTree['route']): ?>active<?php endif; ?>">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> <?php echo $subTree['title']; ?> </p>
                                                    </a> </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6"> <?php echo Breadcrumbs::widget(
                            [
                                'homeLink' => ['label' => '首页', 'url' => ['site/index']],
                                'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                                'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>\n",
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                'options' => [
                                    'class' => 'breadcrumb',
                                ],
                            ]
                        ); ?> </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid"> <?php echo $content; ?> </div>
            </section>
        </div>
        <!-- <footer class="main-footer">
     </footer> -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <?php $this->endBody(); ?>
</body>

</html>
<?php $this->endPage(); ?>