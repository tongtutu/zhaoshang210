<?php
/**
 * 市场信息
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace admin\controllers;

use Exception;
use Yii;
use yii\web\Response;
use yii\data\Pagination;
use bagesoft\functions\TagsFunc;
use bagesoft\functions\UserFunc;
use bagesoft\helpers\Utils;
use bagesoft\functions\VisitFunc;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\models\Customer;
use bagesoft\models\Maintain;
use bagesoft\functions\ProjectFunc;
use bagesoft\functions\CustomerFunc;
use bagesoft\functions\MaintainFunc;
use bagesoft\models\CustomerExt;
use bagesoft\communication\Dccomm;
use yii\web\NotFoundHttpException;
use bagesoft\constant\ProjectConst;
use bagesoft\constant\MessageQueueConst;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CustomerController extends \bagesoft\common\controllers\admin\Base
{
    private $exportFields = [
        'id' => 'ID',
        'username' => '创建人',
        'expand_type' => '拓展类型',
        'partner_name' => '合作伙伴',
        'partner_accept' => '合作伙伴审核',
        'project_name' => '项目名称',
        'company_name' => '公司名称',
        'stars' => '客户星级',
        'tags' => '项目标签',
        'realname' => '客户全名',
        'sex' => '性别',
        'usci_code' => '统一信用代码',
        'job_title' => '职务',
        'phone' => '联系方式1',
        'phone1' => '联系方式2',
        'province' => '省份',
        'city' => '城市',
        'area' => '区域',
        'address' => '联系地址',
        'content' => '项目介绍',
        'steps' => '项目阶段',
        'attach_file' => '附件',
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
        $companyName = trim($request->get('companyName'));
        $realname = trim($request->get('realname'));
        $username = trim($request->get('username'));
        $partnerName = trim($request->get('partnerName'));
        $stars = intval($request->get('stars'));
        $steps = intval($request->get('steps'));
        $expandType = intval($request->get('expandType'));
        $btMgr = trim($request->get('btMgr'));
        $partnerAccept = intval($request->get('partnerAccept'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $customer = new Customer();
        $model = $customer->find()->alias('customer');
        $model->leftJoin(CustomerExt::tableName() . ' ext', 'ext.project_id=customer.id');
        $model->select('customer.*,ext.bt_request,ext.bt_request_respond');

        if ($projectName) {
            $model->andWhere(['like', 'customer.project_name', $projectName]);
        }
        if ($companyName) {
            $model->andWhere(['like', 'customer.company_name', $companyName]);
        }
        if ($realname) {
            $model->andWhere(['like', 'customer.realname', $realname]);
        }
        if ($username) {
            $model->andWhere(['like', 'customer.username', $username]);
        }
        if ($partnerName) {
            $model->andWhere(['like', 'customer.partner_name', $partnerName]);
        }
        if ($stars) {
            $model->andWhere('customer.stars=:stars', ['stars' => $stars]);
        }
        if ($steps) {
            $model->andWhere('customer.steps=:steps', ['steps' => $steps]);
        }

        if ($expandType) {
            $model->andWhere('customer.expand_type=:expandType', ['expandType' => $expandType]);
        }
        if ($btMgr) {
            $model->andWhere('ext.bt_request=:btRequest AND ext.bt_request_respond=:btRequestRespond', ['btRequest' => System::YES, 'btRequestRespond' => System::NO]);
        }
        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'created_at', $dateAt['start'], $dateAt['end']]);
        }
        if ($partnerAccept) {
            $model->andWhere('customer.partner_accept=:partnerAccept', ['partnerAccept' => $partnerAccept]);
        }


        $count = $model->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '20', 'defaultPageSize' => 20]);
        $datalist = $model->offset($pagination->offset)->limit($pagination->limit)->orderBy('customer.id DESC')->asArray()->all();
        return $this->render(
            'index',
            [
                'count' => $count,
                'pagination' => $pagination,
                'datalist' => $datalist,
                'project' => $customer
            ]
        );
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        parent::acl();
        $model = CustomerFunc::getItemById($id);
        $model->scenario = 'update';
        if (false == $model) {
            throw new NotFoundHttpException('记录不存在');
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        if (!is_array($model->tags)) {
            $model->tags = TagsFunc::unformat($model->tags, 'k');
        } elseif (empty($model->tags)) {
            $model->tags = [];
        }
        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 查看详情
     *
     * @param integer $id
     */
    public function actionItem($id)
    {
        parent::acl();
        $model = CustomerFunc::getItemById($id);
        VisitFunc::log($this->session, $model, System::SOURCE_CUSTOMER);
        $model->views += 1;
        $model->save();
        return $this->render(
            'item',
            [
                'model' => $model,
                'maintain' => new Maintain(),
                'maintains' => MaintainFunc::getMaintianListById($model->id, System::SOURCE_CUSTOMER),
                'partner' => UserFunc::getUserById($model->partner_uid),
                'attachFiles' => UploadFunc::getlist(System::UPLOAD_SOURCE_CUSTOMER, $model->id),
            ]
        );
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        try {
            parent::acl();
            $model = CustomerFunc::getItemById($id);
            if (false == $model) {
                throw new NotFoundHttpException('记录不存在');
            }
            (new Dccomm([
                'taskName' => '删除市场信息',
                'act' => MessageQueueConst::PROJECT_DELETE,
            ]))->run([
                        'projectId' => $model->id,
                        'projectName' => $model->project_name,
                        'source' => System::SOURCE_CUSTOMER,
                        'adminName' => $this->admin->username,
                        'uid' => $model->uid,
                        'partnerUid' => $model->partner_uid,
                    ]);
            $model->delete();
            parent::renderSuccessJson([], '删除完成');
        } catch (\Exception $e) {
            parent::renderErrorJson($e->getMessage());
        }
    }


    /**
     * 分配招投标
     *
     * @param integer $id
     * @return void
     */
    public function actionAssignBtManager()
    {
        parent::acl();
        try {
            $request = Yii::$app->request;
            $projectId = intval($request->post('projectId'));
            $model = $this->findModel($projectId);
            $oldManagerId = $model->bt_manager_uid;
            $model->load(Yii::$app->request->post());
            ProjectFunc::assignBtManager($model, $oldManagerId);
            parent::renderSuccessJson([], '分配完成');
        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }

    }


    /**
     * 过户
     *
     * @param integer $id
     * @return void
     */
    public function actionTransfer()
    {
        parent::acl();
        try {
            $request = Yii::$app->request;
            $post = $request->post();
            $transferId = intval($post['transferId']);
            $newUid = intval($post['Customer']['uid']);
            $model = $this->findModel($transferId);
            $newUser = UserFunc::getUserById($newUid);
            if (false == $newUser) {
                throw new Exception('新员工不存在');
            }
            CustomerFunc::transfer($model, $newUser);
            parent::renderSuccessJson([], '过户完成');
        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }

    }


    /**
     * 加载模型
     */
    public function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的记录不存在');
        }
    }

    /**
     * 导出下载
     * @return mixed
     */
    public function actionExportDownload()
    {
        parent::acl();
        set_time_limit(300); // 延长超时时间（秒）
        ini_set('memory_limit', '512M'); // 增加内存限制
        $request = Yii::$app->request;
        $projectName = trim($request->get('projectName'));
        $companyName = trim($request->get('companyName'));
        $realname = trim($request->get('realname'));
        $username = trim($request->get('username'));
        $stars = intval($request->get('stars'));
        $steps = intval($request->get('steps'));
        $partnerAccept = intval($request->get('partnerAccept'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $model = Customer::find()->alias('customer');
        $model->limit(5000);
        $model->select(array_keys($this->exportFields));

        if ($projectName) {
            $model->andWhere(['like', 'customer.project_name', $projectName]);
        }
        if ($username) {
            $model->andWhere(['like', 'customer.username', $username]);
        }
        if ($companyName) {
            $model->andWhere(['like', 'customer.company_name', $companyName]);
        }
        if ($realname) {
            $model->andWhere(['like', 'customer.realname', $realname]);
        }
        if ($stars) {
            $model->andWhere('customer.stars=:stars', ['stars' => $stars]);
        }
        if ($steps) {
            $model->andWhere('customer.steps=:steps', ['steps' => $steps]);
        }
        if ($partnerAccept) {
            $model->andWhere('customer.partner_accept=:partnerAccept', ['partnerAccept' => $partnerAccept]);
        }

        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'created_at', $dateAt['start'], $dateAt['end']]);
        }

        $datalist = $model->orderBy('customer.id DESC')->asArray()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 设置表头
        $header = array_values($this->exportFields);
        $headerKeys = array_keys($this->exportFields);
        $sheet->fromArray([$header], null, 'A1');

        // 将数据写入电子表格并设置单元格数据类型为文本
        $rowIndex = 2;
        foreach ($datalist as $rowData) {
            $colIndex = 0;
            $content = strip_tags($rowData['content']);
            foreach ($headerKeys as $headerKey) {
                $id = $rowData['id'];
                $attachFiles = UploadFunc::getLinkText(System::UPLOAD_SOURCE_CUSTOMER, $id);
                switch ($headerKey) {
                    case 'stars':
                        $cellValue = System::STARS[$rowData[$headerKey]];
                        break;
                    case 'partner_accept':
                        $cellValue = ProjectConst::PARTNER_ACCEPT[$rowData[$headerKey]];
                        break;
                    case 'created_at':
                        $cellValue = date('Y-m-d H:i:s', $rowData[$headerKey]);
                        break;
                    case 'steps':
                        $cellValue = ProjectConst::STEPS[$rowData[$headerKey]];
                        break;
                    case 'content':
                        $cellValue = $content;
                        break;
                    case 'sex':
                        $cellValue = System::SEX[$rowData[$headerKey]];
                        break;
                    case 'realname':
                        $cellValue = ProjectFunc::adminHideName($this->admin, $rowData[$headerKey]);
                        break;
                    case 'phone':
                        $cellValue = ProjectFunc::adminHidePhone($this->admin, $rowData[$headerKey]);
                        break;
                    case 'phone1':
                        $cellValue = ProjectFunc::adminHidePhone($this->admin, $rowData[$headerKey]);
                        break;
                    case 'address':
                        $cellValue = ProjectFunc::adminHideAll($this->admin, $rowData[$headerKey]);
                        break;
                    case 'company_name':
                        $cellValue = ProjectFunc::adminHideAll($this->admin, $rowData[$headerKey]);
                        break;
                    case 'usci_code':
                        $cellValue = ProjectFunc::adminHideAll($this->admin, $rowData[$headerKey]);
                        break;
                    case 'expand_type':
                        $cellValue = ProjectConst::EXPAND_TYPE[$rowData[$headerKey]];
                        break;
                    case 'attach_file':
                        $cellValue = $attachFiles;
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
        $filename = 'export_customer_' . date('YmdHis') . '.xlsx';
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
        $response->data = ob_get_clean(); // 捕获缓冲区内容作为响应数据
        return $response; 
        Yii::$app->end();
    }
}
