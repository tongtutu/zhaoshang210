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
use bagesoft\models\Visit;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use yii\web\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class VisitController extends \bagesoft\common\controllers\app\Base
{
    private $exportFields = [
        'id' => 'ID',
        'visit_user' => '访问者姓名',
        'project_source' => '项目类型',
        'project_name' => '项目名称',
        'created_at' => '访问时间',
    ];

    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $request = Yii::$app->request;
        $visitUser = trim($request->get('visitUser'));
        $projectName = trim($request->get('projectName'));

        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $model = Visit::find();
        $model->where('uid=:uid', ['uid' => $this->session['uid']]);

        if ($visitUser) {
            $model->andWhere('visit_user=:visitUser', ['visitUser' => $visitUser]);
        }
        if ($projectName) {
            $model->andWhere(['like', 'project_name', '%' . $projectName . '%', false]);
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
        $request = Yii::$app->request;
        $visitUser = trim($request->get('visitUser'));
        $projectName = trim($request->get('projectName'));

        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $model = Visit::find();
        $model->limit(System::EXPORT_EXCEL_NUM);
        $model->select($headerKeys);
        $model->where('uid=:uid', ['uid' => $this->session['uid']]);

        if ($visitUser) {
            $model->andWhere('visit_user=:visitUser', ['visitUser' => $visitUser]);
        }
        if ($projectName) {
            $model->andWhere(['like', 'project_name', '%' . $projectName . '%', false]);
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
                    case 'visit_source':
                        $cellValue = System::SOURCE_APP[$cellData];
                        break;
                    case 'project_source':
                        $cellValue = System::SOURCE[$cellData];
                        break;
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
        $filename = 'export_visit_' . date('YmdHis') . '.xlsx';
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
