<?php
/**
 * 跟进维护
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use Yii;
use yii\web\Response;
use yii\data\Pagination;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\functions\InvestFunc;
use bagesoft\functions\TagsFunc;
use bagesoft\functions\UploadFunc;
use bagesoft\models\Maintain;
use yii\bootstrap5\ActiveForm;
use bagesoft\functions\CustomerFunc;
use bagesoft\functions\MaintainFunc;
use yii\web\NotFoundHttpException;
use bagesoft\constant\ProjectConst;
use bagesoft\models\MaintainUserMap;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use bagesoft\models\Invest;
use bagesoft\models\InvestUserMap;

class MaintainController extends \bagesoft\common\controllers\admin\Base
{
    private $exportFields = [
        'id' => 'ID',
        'source' => '来源',
        'username' => '跟进人',
        'partner_name' => '伙伴名称',
        'project_name' => '项目名称',
        'steps' => '项目阶段',
        'attach_file' => '附件',
        'typeid' => '跟进类型',
        'content' => '情况描述',
        'state' => '审核状态',
        'remind_time' => '下次跟进提醒',
        'project_assess' => '所属考核项目',
        'created_at' => '入库时间',
    ];

    /**
     * 列表
     */
    public function actionIndex()
    {
        parent::acl();
        $request = Yii::$app->request;
        $projectName = trim($request->get('projectName'));
        $partnerName = trim($request->get('partnerName'));
        $projectAssess = intval($request->get('assess'));
        $typeid = trim($request->get('typeid'));
        $state = intval($request->get('state'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        //通过所属考核项目查询项目ID
        $projectIds = [];
        if($projectAssess){
            $projectIds = Invest::find()
                ->where(['project_assess' => $projectAssess])
                ->select('id')
                ->column();
        }
        // var_dump($projectAssess);
        // var_dump($projectIds);
        $model = Maintain::find()->alias('maintain');        

        if($projectIds){
            $model->andWhere(['maintain.project_id'=> $projectIds]);
        }
        if ($projectName) {
            $model->andWhere(['like', 'maintain.project_name', $projectName]);
        }
        if ($partnerName) {
            $model->andWhere('maintain.username=:partnerName', ['partnerName' => $partnerName]);
        }
        if($typeid){
            $model->andWhere('maintain.typeid=:typeid', ['typeid' => $typeid]);
        }
        if ($state) {
            $model->andWhere('maintain.state=:state', ['state' => $state]);
        }
        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'maintain.created_at', $dateAt['start'], $dateAt['end']]);
        }
        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);

        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('maintain.id DESC')->asArray()->all();
        //echo $model->createCommand()->getRawSql();
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
     * 详情
     */
    public function actionItem($id)
    {
        parent::acl();
        $model = MaintainFunc::getItemById($id);
        if (false == $model) {
            throw new \Exception('记录不存在');
        }

        if ($model->source == System::SOURCE_CUSTOMER) {
            $project = CustomerFunc::getItemById($model->project_id);
        } else {
            $project = InvestFunc::getItemById($model->project_id);
        }
        if (false == $project) {
            throw new NotFoundHttpException('项目不存在');
        }

        return $this->render(
            'item',
            [
                'model' => $model,
                'project' => $project,
                'attachFiles' => UploadFunc::getlist(System::UPLOAD_SOURCE_MAINTAIN, $model->id)
            ]
        );
    }

    /**
     * 更新维护校验
     *
     * @return void
     */
    public function actionValidate()
    {
        $model = new Maintain();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        parent::acl();
        $model = MaintainFunc::getItemById($id);
        if (false == $model) {
            throw new NotFoundHttpException('记录不存在');
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('index');
            }
        }
        if ($model->source == System::SOURCE_CUSTOMER) {
            $project = CustomerFunc::getItemById($model->project_id);
        } else {
            $project = InvestFunc::getItemById($model->project_id);
        }
        return $this->render(
            'update',
            [
                'project' => $project,
                'model' => $model,
                'source' => $model->source,
            ]
        );
    }

    /**
     * 删除
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $model = MaintainFunc::getItemById($id);
            if (false == $model) {
                throw new \Exception('记录不存在');
            }
            MaintainUserMap::deleteAll('maintain_id=:maintainId', ['maintainId' => $model->id]);
            $model->delete();
            parent::renderSuccessJson([], '删除完成');
        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }

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
        $projectName = trim($request->get('projectName'));
        $partnerName = trim($request->get('partnerName'));
        $state = intval($request->get('state'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);

        $model = Maintain::find()->alias('maintain');
        $model->limit(System::EXPORT_EXCEL_NUM);
        $model->select('*');

        if ($projectName) {
            $model->andWhere(['like', 'maintain.project_name', $projectName]);
        }
        if ($partnerName) {
            $model->andWhere(['like', 'maintain.username', $partnerName]);
        }
        if ($state) {
            $model->andWhere('maintain.state=:state', ['state' => $state]);
        }
        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'maintain.created_at', $dateAt['start'], $dateAt['end']]);
        }

        $datalist = $model->orderBy('maintain.id DESC')->asArray()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 设置表头
        $header = array_values($this->exportFields);
        $headerKeys = array_keys($this->exportFields);
        $sheet->fromArray([$header], null, 'A1');
        $rowIndex = 2;
        foreach ($datalist as $rowData) {
            $colIndex = 0;
            foreach ($headerKeys as $headerKey) {
                $id = $rowData['id'];
                $attachFiles = UploadFunc::getLinkText(System::UPLOAD_SOURCE_MAINTAIN, $id);
                switch ($headerKey) {
                    case 'state':
                        $cellValue = ProjectConst::MAINTAIN_STATUS[$rowData['state']];
                        break;
                    case 'source':
                        $cellValue = System::SOURCE[$rowData['source']];
                        break;
                    case 'steps':
                        $cellValue = ProjectConst::STEPS[$rowData['steps']];
                        break;
                    case 'typeid':
                        $cellValue = ProjectConst::MAINTAIN_TYPE[$rowData['typeid']];
                        break;
                    case 'created_at':
                        $cellValue = date('Y-m-d H:i:s', $rowData['created_at']);
                        break;
                    case 'attach_file':
                        $cellValue = $attachFiles;
                        break;
                    case 'content':
                        $cellValue = $rowData[$headerKey];
                        break;
                    default:
                        $cellValue = $rowData[$headerKey];
                        break;
                }
                $sheet->setCellValueExplicitByColumnAndRow($colIndex + 1, $rowIndex, $cellValue, DataType::TYPE_STRING);

                $colIndex++;
            }
            $rowIndex++;

        }

        // 导出Excel文件
        $filename = 'export_maintain_' . date('YmdHis') . '.xlsx';
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
