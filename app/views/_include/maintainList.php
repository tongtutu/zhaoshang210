<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\constant\ProjectConst;

?>
<?php if ($maintains['datalist']): ?>
    <div class="row">
        <div class="col-lg-12">
            <section class="content">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h1 class="card-title">跟进维护记录</h1>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 ">

                                <?php foreach ($maintains['datalist'] as $key => $row): ?>
                                    <?php $attachFiles = UploadFunc::getlist(System::UPLOAD_SOURCE_MAINTAIN, $row['id']); ?>
                                    <div class="callout callout-info">
                                        <div>
                                            <strong class="text-primary"> <?php echo $row['username']; ?></strong>
                                            <small class="description">
                                                在 <?php echo date('Y-m-d H:i', $row['created_at']); ?>
                                                发布项目动态</small> <span
                                                class="badge badge-info"><?php echo ProjectConst::MAINTAIN_TYPE[$row['typeid']]; ?></span>
                                            <span
                                                class="badge badge-success"><?php echo ProjectConst::STEPS[$row['steps']]; ?></span>
                                            <span
                                                class="badge <?php if ($row['state'] == ProjectConst::MAINTAIN_STATUS_WAIT): ?>badge-warning<?php else: ?>badge-primary<?php endif ?>"><?php echo ProjectConst::MAINTAIN_STATUS[$row['state']]; ?></span>
                                        </div>
                                        <div>
                                            <?php if ($row['content']): ?>
                                                <blockquote class="quote-secondary">
                                                    <?php echo nl2br(Html::encode($row['content'])); ?>
                                                </blockquote>
                                            <?php endif ?>
                                            <?php if ($attachFiles): ?>
                                                <div class="attachFile paddingLeft">
                                                    <ul>
                                                        <?php foreach ($attachFiles as $key => $file): ?>
                                                            <li> <a href="<?php echo Url::toRoute(['upload/getfile', 'id' => $file->id]); ?>"
                                                                    target="_blank"><i class="fa fa-paperclip"></i>
                                                                    <?php echo Html::encode($file->real_name); ?>

                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            </div>


                        </div>
                    </div>

                    <div class="mailbox-controls">
                        <div class="float-right">
                            <?php echo LinkPager::widget(
                                [
                                    'pagination' => $maintains['pagination'],
                                    'options' => ['class' => 'pagination'],
                                    'linkOptions' => ['class' => 'page-link'],
                                    'activePageCssClass' => ' page-item',
                                    'disabledPageCssClass' => ' active',
                                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                                    'disableCurrentPageButton' => true,
                                ]
                            ); ?>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </section>
        </div>
    </div>
<?php endif; ?>