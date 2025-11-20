<?php

use yii\bootstrap5\Tabs;
echo Tabs::widget([
    'items' => [
        [
            'label' => '项目详情',
            'content' => $this->render(
                '_include/infomation',
                [
                    'model' => $model,
                    'maintain' => $maintain,
                    'maintains' => $maintains,
                    'partner' => $partner,
                    'attachFiles' => $attachFiles,
                ]
            ),
            'active' => true,
        ],
    ],
]);

?>