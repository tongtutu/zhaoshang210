<?php
/**
 * 市场信息
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\Response;
use yii\data\Pagination;
use bagesoft\functions\TagsFunc;
use bagesoft\functions\UserFunc;
use bagesoft\helpers\Utils;
use bagesoft\functions\VisitFunc;
use bagesoft\constant\System;
use bagesoft\functions\UploadFunc;
use bagesoft\models\Customer;
use bagesoft\models\CustomerDraft;
use bagesoft\functions\ProjectFunc;
use yii\bootstrap5\ActiveForm;
use bagesoft\functions\CustomerFunc;
use bagesoft\functions\MaintainFunc;
use bagesoft\constant\UserConst;
use bagesoft\communication\Dccomm;
use yii\web\NotFoundHttpException;
use bagesoft\constant\ProjectConst;
use bagesoft\models\CustomerUserMap;
use bagesoft\constant\MessageQueueConst;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use bagesoft\models\form\maintain\BtMgrForm;
use bagesoft\models\form\maintain\NormalForm;

class CustomerController extends \bagesoft\common\controllers\app\Base
{
    private $exportFields = [
        'id' => 'ID',
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
        $partnerName = trim($request->get('partnerName'));
        $expandType = intval($request->get('expandType'));
        $realname = trim($request->get('realname'));
        $channelId = intval($request->get('channelId'));
        $stars = intval($request->get('stars'));
        $steps = intval($request->get('steps'));
        $partnerAccept = intval($request->get('partnerAccept'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $model = CustomerUserMap::find()->alias('userMap');
        $model->leftJoin(Customer::tableName() . 'customer', 'userMap.project_id=customer.id');
        $model->select('userMap.uid,userMap.project_id,userMap.role_type,customer.*');
        $model->where('userMap.uid=:uid AND customer.deleted=:deleted', ['uid' => $this->session['uid'], 'deleted' => System::DELETE_LEVEL_1]);

        if ($projectName) {
            $model->andWhere(['like', 'customer.project_name', $projectName]);
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

        if ($expandType) {
            $model->andWhere('customer.expand_type=:expandType', ['expandType' => $expandType]);
        }
        if ($partnerName) {
            $model->andWhere(['like', 'customer.partner_name', $partnerName]);
        }
        if ($partnerAccept) {
            $model->andWhere('customer.partner_accept=:partnerAccept', ['partnerAccept' => $partnerAccept]);
        }
        if ($dateAt['start'] > 0 && $dateAt['end'] > 0) {
            $model->andWhere(['between', 'created_at', $dateAt['start'], $dateAt['end']]);
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
            ]
        );
    }

    /**
     * 字段检测
     *
     * @return mixed
     */
    public function actionValueCheck()
    {
        $request = Yii::$app->request;
        $inputValue = trim($request->get('inputValue'));
        $fieldName = trim($request->get('fieldName'));

        $model = Customer::find();
        switch ($fieldName) {
            case 'usciCode':
                $model->where('usci_code=:usciCode', ['usciCode' => $inputValue]);
                break;
            case 'phone':
                $model->where('phone=:phone OR phone1=:phone1', ['phone' => $inputValue,'phone1' => $inputValue]);
                break;
            case 'address':
                $model->where('address=:address', ['address' => $inputValue]);
                break;
        }
        $error = '请注意：' . Html::encode($inputValue) . ' 系统中已存在';

        $datalist = $model->all();

        if ($datalist) {
            parent::renderErrorJson($error);
        } else {
            parent::renderSuccessJson();
        }
    }
    /**
     * 草稿
     */
    public function actionSaveDraft()
    {
        $request = Yii::$app->request->post();
        $uid = $this->session['uid'];
        $customerData = $request['Customer'] ?? [];
        //var_dump($investData);
        $model = new Customer();
        if ($model->load($request)) {
            CustomerDraft::deleteAll(['uid' => $uid]);
            $draft = new CustomerDraft();
            $draft->uid = $uid;
            $draft->username = $this->session['username'];
            $draft->realname = $customerData['realname'] ?? '';
            $draft->sex = $customerData['sex'] ?? '';
            $draft->job_title = $customerData['job_title'] ?? '';
            $draft->stars = $customerData['stars'] ?? '';
            $draft->phone = $customerData['phone'] ?? '';
            $draft->phone1 = $customerData['phone1'] ?? '';
            $draft->project_name = $customerData['project_name'] ?? '';
            $draft->company_name = $customerData['company_name'] ?? '';
            $draft->usci_code = $customerData['usci_code'] ?? '';
            $draft->province = $customerData['province'] ?? '';
            $draft->city = $customerData['city'] ?? '';
            $draft->area = $customerData['area'] ?? '';
            $draft->address = $customerData['address'] ?? '';
            $draft->content = $customerData['content'] ?? '';
            $draft->partner_uid = $customerData['partner_uid'] ?? 0;
            $draft->expand_type = $customerData['expand_type'] ?? '';
            $draft->attach_file = $customerData['attach_file'] ?? '';
            $draft->tags = $customerData['tags'] ?? '';
            $draft->scenario = 'draft';

            if ($draft->save(false)) { // false 跳过重复验证（已validate）
                Yii::$app->session->setFlash('publishSuccess', '发布成功');
                return $this->asJson(['code' => 200, 'msg' => '草稿保存成功']);
            } else {
                return $this->asJson([
                    'code' => -2,
                    'msg' => '草稿保存失败：' . implode(';', $model->getFirstErrors()),
                    'data' => null
                ]);
            }
        }
        
    }
    /**
     * 获取草稿
     */
    public function actionGetLastDraft()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $draft = CustomerDraft::find()
            ->where(['uid' => $this->session['uid']])
            ->one();
        // 2. 构造invest格式返回（前端可直接用invest[]接收）
        if ($draft) {
            return [
                'code' => 200,
                'data' => [
                    // 保持invest键名，前端无需修改
                        'id' => $draft->id,
                        'realname' => $draft->realname,
                        'sex' => $draft->sex,
                        'job_title' => $draft->job_title,
                        'tags' => $draft->tags,
                        'stars' => $draft->stars,
                        'phone' => $draft->phone,
                        'phone1' => $draft->phone1,
                        'project_name' => $draft->project_name,
                        'company_name' => $draft->company_name,
                        'usci_code' => $draft->usci_code,
                        'province' => $draft->province,
                        'city' => $draft->city,
                        'area' => $draft->area,
                        'address' => $draft->address,
                        'content' => $draft->content,
                        'partner_uid' => $draft->partner_uid,
                        'expand_type' => $draft->expand_type,
                        'attach_file' => $draft->attach_file,
                    ]
            ];
        } else {
            return ['code' => 404, 'msg' => '暂无草稿'];
        }
    }
    /**
     * 录入
     */
    public function actionCreate()
    {
        parent::acl();
        $model = new Customer();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {

            $model->uid = $this->session['uid'];
            $model->username = $this->session['username'];
            // $model->tags = $model->tags ? TagsFunc::format($model->tags) : '';
            if ($model->validate()) {
                if ($model->save()) {
                    (new Dccomm([
                        'taskName' => '创建市场信息',
                        'act' => MessageQueueConst::PROJECT_CREATE,
                    ]))->run([
                                'projectId' => $model->id,
                                'source' => System::SOURCE_CUSTOMER,
                            ]);
                    CustomerDraft::deleteAll(['uid' => $this->session['uid']]);
                    Yii::$app->session->setFlash('publishSuccess', '发布成功');
                    return $this->redirect(['index']);
                }
            }
        }
        $model->loadDefaultValues();
        $model->tags = $model->tags ?: [];

        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        parent::acl();
        $model = CustomerFunc::getItemByIdAndUid($id, $this->session['uid']);
        if (false == $model) {
            throw new NotFoundHttpException('记录不存在');
        }
        $model->scenario = 'update';
        $oldAttributes = $model->attributes;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {

                    if ($oldAttributes['partner_uid'] != $model->partner_uid) {
                        //更换后的合作伙伴，发送消息通知
                        (new Dccomm([
                            'taskName' => '创建市场信息',
                            'act' => MessageQueueConst::PROJECT_CREATE,
                        ]))->run([
                                    'projectId' => $model->id,
                                    'source' => System::SOURCE_CUSTOMER,
                                ]);
                        CustomerFunc::updateUserMap($model->id, $model->partner_uid, System::PARTNER);
                    }

                    return $this->redirect(['index']);
                }
            }
        }
        if (!is_array($model->tags)) {
            $model->tags = TagsFunc::unformat($model->tags, 'k');
        } elseif (empty($model->tags)) {
            $model->tags = [];
        }
        if ($model->partner_accept == ProjectConst::PARTNER_ACCEPT_APPOVE) {
            $partnerDisabled = true;
        } else {
            $partnerDisabled = false;
        }
        return $this->render(
            'update',
            [
                'model' => $model,
                'partnerDisabled' => $partnerDisabled,
            ]
        );
    }

    /**
     * 查看详情
     *
     * @param integer $id
     * @return string
     */
    public function actionItem($id)
    {
        parent::acl();
        $uid = $this->session['uid'];
        $model = CustomerFunc::getItemByUserMap($id, $uid);
        if (false == $model) {
            throw new NotFoundHttpException('记录不存在');
        }
        $customer = $model->customer;
        VisitFunc::log($this->session, $customer, System::SOURCE_CUSTOMER);
        $customer->views += 1;
        $customer->save();
        $isBtManager = CustomerFunc::getBtManager($customer->id, $uid);
        if ($isBtManager) {
            $maintain = new BtMgrForm();
        } else {
            $maintain = new NormalForm();
        }
        $maintain->steps = $customer->steps;
        return $this->render(
            'item',
            [
                'model' => $customer,
                'maintain' => $maintain,
                'isBtManager' => $isBtManager,
                'maintains' => MaintainFunc::getMaintianListById($model->id, System::SOURCE_CUSTOMER),
                'partner' => UserFunc::getUserById(UserFunc::getPartnerUid($uid, $customer)),
                'attachFiles' => UploadFunc::getlist(System::UPLOAD_SOURCE_CUSTOMER, $customer->id),
            ]
        );
    }

    /**
     * 内容删除
     */
    public function actionDelete($id)
    {
        try {
            parent::acl();

            $model = CustomerFunc::getItemByIdAndUid($id, $this->session['uid']);
            if ($model->partner_accept == System::APPROVE) {
                $model->deleted = 2;
                $model->save();
            } else {
                $model->delete();
            }

            parent::renderSuccessJson([], '删除完成');
        } catch (\Exception $e) {
            parent::renderErrorJson($e->getMessage());
        }
    }

    /**
     * 导出下载
     * @return mixed
     */
    public function actionExportDownload()
    {

        parent::acl();

        $uid = $this->session['uid'];
        $request = Yii::$app->request;
        $projectName = trim($request->get('projectName'));
        $companyName = trim($request->get('companyName'));
        $realname = trim($request->get('realname'));
        $stars = intval($request->get('stars'));
        $steps = intval($request->get('steps'));
        $partnerAccept = intval($request->get('partnerAccept'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);

        $model = CustomerUserMap::find()->alias('userMap');
        $model->limit(System::EXPORT_EXCEL_NUM);
        $model->leftJoin(Customer::tableName() . 'customer', 'userMap.project_id=customer.id');
        $model->select('customer.id,customer.uid,customer.partner_name,customer.partner_accept,customer.project_name,customer.company_name,customer.stars,customer.tags,customer.realname,customer.sex,customer.usci_code,customer.job_title,customer.phone,customer.phone1,customer.province,customer.city,customer.area,customer.address,customer.content,customer.steps,customer.created_at');
        $model->where('userMap.uid=:uid AND customer.deleted=:deleted', ['uid' => $this->session['uid'], 'deleted' => System::DELETE_LEVEL_1]);

        if ($projectName) {
            $model->andWhere(['like', 'customer.project_name', $projectName]);
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
            $userId = $rowData['uid'];
            $content = strip_tags($rowData['content']);
            $realname = ProjectFunc::hideName($userId, $uid, $rowData['realname']);
            $phone = ProjectFunc::hideName($userId, $uid, $rowData['phone']);
            $phone1 = ProjectFunc::hideName($userId, $uid, $rowData['phone1']);
            $address = ProjectFunc::hideName($userId, $uid, $rowData['address']);
            $companyName = ProjectFunc::hideName($userId, $uid, $rowData['company_name']);
            $usciCode = ProjectFunc::hideName($userId, $uid, $rowData['usci_code']);

            $colIndex = 0;
            foreach ($headerKeys as $headerKey) {

                switch ($headerKey) {
                    case 'realname':
                        $cellValue = $realname;
                        break;
                    case 'phone':
                        $cellValue = $phone;
                        break;
                    case 'phone1':
                        $cellValue = $phone1;
                        break;
                    case 'address':
                        $cellValue = $address;
                        break;
                    case 'company_name':
                        $cellValue = $companyName;
                        break;
                    case 'content':
                        $cellValue = $content;
                        break;
                    case 'usci_code':
                        $cellValue = $usciCode;
                        break;
                    case 'stars':
                        $cellValue = System::STARS[$rowData['stars']];
                        break;
                    case 'partner_accept':
                        $cellValue = ProjectConst::PARTNER_ACCEPT[$rowData['partner_accept']];
                        break;
                    case 'created_at':
                        $cellValue = date('Y-m-d H:i:s', $rowData['created_at']);
                        break;
                    case 'steps':
                        $cellValue = ProjectConst::STEPS[$rowData['steps']];
                        break;
                    case 'sex':
                        $cellValue = System::SEX[$rowData['sex']];
                        break;
                    case 'tags':
                        $v = TagsFunc::unformat($rowData['tags'], 'v');
                        $cellValue = implode('，', $v);
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
        Yii::$app->end();
    }
}
