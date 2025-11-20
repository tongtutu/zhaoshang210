<?php
/**
 * 会员
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\data\Pagination;
use bagesoft\helpers\Utils;
use bagesoft\models\Message;
use bagesoft\constant\System;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class MessageController extends \bagesoft\common\controllers\app\Base
{
    private $exportFields = [
        'id' => 'ID',
        'content' => '内容',
        'created_at' => '接收时间',
    ];

    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();

        $model = Message::find();
        $request = Yii::$app->request;
        $username = trim($request->get('username'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);

        $model->andWhere('uid=:uid', ['uid' => $this->session['uid']]);
        if ($username) {
            $model->andWhere(['like', 'content', '%' . $username . '%', false]);
        }

        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'created_at', $dateAt['start'], $dateAt['end']]);
        }

        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all();
        return $this->render(
            'index',
            [
                'count' => $count,
                'pagination' => $pagination,
                'datalist' => $datalist,
            ]
        );
    }



    /**
     * 导出下载
     * @return mixed
     */
    public function actionExportDownload()
    {
        parent::acl();
        $headerKeys = array_keys($this->exportFields);
        $model = Message::find();
        $model->limit(System::EXPORT_EXCEL_NUM);
        $request = Yii::$app->request;
        $username = trim($request->get('username'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);

        $model->andWhere('uid=:uid', ['uid' => $this->session['uid']]);
        if ($username) {
            $model->andWhere(['like', 'content', '%' . $username . '%', false]);
        }

        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'created_at', $dateAt['start'], $dateAt['end']]);
        }

        $datalist = $model->orderBy('id DESC')->asArray()->all();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 设置表头
        $header = array_values($this->exportFields);
        $sheet->fromArray([$header], null, 'A1');
        $rowIndex = 2;
        foreach ($datalist as $rowData) {
            $colIndex = 0;
            foreach ($rowData as $cellData) {
                $fieldName = $headerKeys[$colIndex];
                switch ($fieldName) {
                    case 'created_at':
                        $cellValue = date('Y-m-d H:i:s', $cellData);
                        break;
                    default:
                        $cellValue = $cellData;
                        break;
                }
                $sheet->setCellValueExplicitByColumnAndRow($colIndex + 1, $rowIndex, $cellValue, DataType::TYPE_STRING);
                $colIndex++;
            }
            $rowIndex++;
        }

        // 导出Excel文件
        $filename = 'export_message_' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // 输出Excel文件
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
        Yii::$app->end();
    }
}
