<?php
/**
 * 跟进维护
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     © 2007-2099 cookman.cn. All rights reserved.
 */

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\data\Pagination;
use bagesoft\helpers\Utils;
use bagesoft\constant\System;
use bagesoft\functions\InvestFunc;
use bagesoft\functions\UploadFunc;
use bagesoft\models\Maintain;
use yii\bootstrap5\ActiveForm;
use bagesoft\functions\CustomerFunc;
use bagesoft\functions\MaintainFunc;
use bagesoft\constant\UserConst;
use bagesoft\communication\Dccomm;
use yii\web\NotFoundHttpException;
use bagesoft\constant\ProjectConst;
use bagesoft\models\MaintainUserMap;
use bagesoft\models\CustomerUserMap;
use bagesoft\models\InvestUserMap;
use bagesoft\constant\MessageQueueConst;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use bagesoft\models\form\maintain\BtMgrForm;
use bagesoft\models\form\maintain\NormalForm;

class MaintainController extends \bagesoft\common\controllers\app\Base
{
    private $exportFields = [
        'id' => 'ID',
        'source' => '来源',
        'username' => '跟进人',
        'partner_name' => '伙伴名称',
        'project_name' => '项目名称',
        'typeid' => '跟进类型',
        'steps' => '项目阶段',
        'content' => '情况描述',
        'state' => '审核状态',
        'remind_time' => '下次跟进提醒',
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
        $parterName = trim($request->get('parterName'));
        $typeid = trim($request->get('typeid'));
        $state = intval($request->get('state'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $uid = $this->session['uid'];
        // 1. 获取当前用户自己的维护记录ID（通过用户映射表）
        $personalMaintainIds = MaintainUserMap::find()
            ->where(['uid' => $uid])
            ->select('maintain_id')
            ->column();
        // 2. 获取当前用户关联的项目ID（根据实际业务调整关联字段）
        // 假设用户通过Customer_user_map表关联市场项目
        $relatedProjectIds = CustomerUserMap::find()
            ->where(['uid' => $uid])
            ->select('project_id')
            ->column();
        // 3. 获取关联市场项目的所有维护记录ID
        $projectRelatedMaintainIds = [];
        if (!empty($relatedProjectIds)) {
            $projectRelatedMaintainIds = Maintain::find()
                ->where(['and',['project_id' => $relatedProjectIds],['source' => 1]])
                ->select('id')
                ->column();
        }
        //4. 获取当前用户关联的项目ID（根据实际业务调整关联字段）
        // 假设用户通过Invest_user_map表关联招商项目
        $relatedInvestIds = InvestUserMap::find()
            ->where(['uid' => $uid])
            ->select('project_id')
            ->column();
        // 5. 获取关联招商项目的所有维护记录ID
        $InvestRelatedMaintainIds = [];
        if (!empty($relatedInvestIds)) {
            $InvestRelatedMaintainIds = Maintain::find()
                ->where(['and',['project_id' => $relatedInvestIds],['source' => 2]])
                ->select('id')
                ->column();
        }
        // 6. 合并三类记录ID并去重
        $allMaintainIds = array_unique(array_merge(
            $personalMaintainIds,
            $projectRelatedMaintainIds,
            $InvestRelatedMaintainIds
        ));
        // 构建查询
        $model = Maintain::find()->alias('maintain')
            ->where(['maintain.id' => $allMaintainIds]); // 核心条件：仅查询有权限的记录
        //旧逻辑
        //$model = MaintainUserMap::find()->alias('userMap');
        //$model->leftJoin(Maintain::tableName() . 'maintain', 'userMap.maintain_id=maintain.id');
        //$model->select('userMap.uid,userMap.maintain_id,maintain.*');
        //$model->where('userMap.uid=:uid', ['uid' => $this->session['uid']]);

        if ($projectName) {
            $model->andWhere(['like', 'maintain.project_name', $projectName]);
        }
        if ($parterName) {
            $model->andWhere('maintain.username=:parterName', ['parterName' => $parterName]);
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

        $maintain = new Maintain();
        return $this->render(
            'index',
            [
                'count' => $count,
                'pagination' => $pagination,
                'datalist' => $datalist,
                'maintain' => $maintain
            ]
        );
    }

    /**
     * 详情
     */
    public function actionItem($id)
    {
        parent::acl();
        $model = Maintain::find()->alias('maintain')
            ->where(['id' => $id]); // 核心条件：仅查询有权限的记录
        if (false == $model) {
            throw new \Exception('记录不存在');
        }
        $maintain = $model->one();

        if ($maintain->source == System::SOURCE_CUSTOMER) {
            $project = CustomerFunc::getItemById($maintain->project_id);
        } else {
            $project = InvestFunc::getItemById($maintain->project_id);
        }
        if (false == $project) {
            throw new NotFoundHttpException('项目不存在');
        }

        return $this->render(
            'item',
            [
                'model' => $maintain,
                'project' => $project,
                'attachFiles' => UploadFunc::getlist(System::UPLOAD_SOURCE_MAINTAIN, $maintain->id)
            ]
        );
    }

    /**
     * 更新维护校验
     *
     */
    public function actionValidate()
    {
        if ($this->user->gid == System::BT_MANAGER) {
            $model = new BtMgrForm();
        } else {
            $model = new NormalForm();
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * 更新维护
     *
     * @return void
     */
    public function actionSave()
    {
        try {
            parent::acl();
            $request = Yii::$app->request;
            $uid = $this->session['uid'];
            $projectId = intval($request->post('projectId'));
            $source = intval($request->post('source'));

            if ($source == System::SOURCE_CUSTOMER) {
                $project = CustomerFunc::getItemById($projectId);
                $isBtManager = CustomerFunc::getBtManager($projectId, $uid);
            } else {
                $project = InvestFunc::getItemByIdAndUid($projectId, $uid);
                $isBtManager = false;
            }
            if (false == $project) {
                throw new \Exception('信息不存在');
            }
            if ($isBtManager) {
                $maintainForm = new BtMgrForm();
            } else {
                $maintainForm = new NormalForm();
            }

            if ($maintainForm->load(Yii::$app->request->post()) && $maintainForm->validate()) {
                $model = new Maintain();
                $model->attributes = $maintainForm->attributes;
                if ($model->validate()) {

                    $changeUser = MaintainFunc::selectUser($uid, $project);

                    $model->uid = intval($uid);
                    $model->username = (string) $this->session['username'];
                    $model->partner_uid = $changeUser['partner_uid'];
                    $model->partner_name = $changeUser['partner_name'];
                    if ($source == System::SOURCE_INVEST || $isBtManager) {
                        //如果是招商信息 / 招投标经理直接通过【免审核】
                        $model->state = ProjectConst::MAINTAIN_STATUS_PASS;
                    }
                    if ($isBtManager || $source == System::SOURCE_INVEST) {
                        //投标经理直接通过审核且更新项目信息
                        $project->steps = $model->steps;
                        $project->maintain_at = time();
                        $project->save();
                    }
                    $model->source = $source;
                    $model->project_id = $project->id;
                    $model->project_name = $project->project_name;
                    if (!$model->save()) {
                        throw new \Exception('保存失败');
                    }
                    //发送消息
                    //市场信息才会有审核推送
                    if ($source == System::SOURCE_CUSTOMER && $model->partner_uid > 0) {
                        (new Dccomm([
                            'taskName' => '跟进维护发布',
                            'act' => MessageQueueConst::MAINTAIN_CREATE,
                        ]))->run([
                                    'username' => $model->username,
                                    'partnerUid' => $model->partner_uid,
                                    'projectName' => $model->project_name,
                                ]);

                    }

                    //招投标阶段通知管理
                    if ($model->steps == ProjectConst::STEPS_4 && $model->bt_demand == System::YES) {
                        (new Dccomm([
                            'taskName' => '招投标阶段_通知管理员',
                            'act' => MessageQueueConst::PROJECT_STEPS_BT_TOADMIN,
                        ]))->run([
                                    'projectName' => $model->project_name,
                                    'sourceName' => System::SOURCE[$model->source],
                                ]);
                    }

                    parent::renderSuccessJson([], '维护信息提交成功');
                } else {
                    $error = Utils::getModelErrors($model);
                    throw new \Exception('操作未成功：' . $error);
                }
            } else {
                $error = Utils::getModelErrors($maintainForm);
                throw new \Exception('操作未成功：' . $error);
            }

        } catch (\Throwable $th) {
            parent::renderErrorJson($th->getMessage());
        }
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        parent::acl();
        $model = MaintainFunc::getItemByIdAndUid($id, (int) $this->session['uid']);
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
            $model = MaintainFunc::getItemByIdAndUid($id, (int) $this->session['uid']);
            if (false == $model) {
                throw new \Exception('记录不存在');
            }

            //发送消息
            (new Dccomm([
                'taskName' => '跟进维护删除',
                'act' => MessageQueueConst::MAINTAIN_DELETE,
            ]))->run([
                        'username' => $model->username,
                        'partnerUid' => $model->partner_uid,
                        'projectName' => $model->project_name,
                    ]);

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
        $parterName = trim($request->get('parterName'));
        $state = intval($request->get('state'));
        $dateAt = trim($request->get('date'));
        $dateAt = Utils::dateRange($dateAt);
        $uid = $this->session['uid'];
        // 1. 获取当前用户自己的维护记录ID（通过用户映射表）
        $personalMaintainIds = MaintainUserMap::find()
            ->where(['uid' => $uid])
            ->select('maintain_id')
            ->column();
        // 2. 获取当前用户关联的项目ID（根据实际业务调整关联字段）
        // 假设用户通过Customer_user_map表关联市场项目
        $relatedProjectIds = CustomerUserMap::find()
            ->where(['uid' => $uid])
            ->select('project_id')
            ->column();
        // 3. 获取关联市场项目的所有维护记录ID
        $projectRelatedMaintainIds = [];
        if (!empty($relatedProjectIds)) {
            $projectRelatedMaintainIds = Maintain::find()
                ->where(['and',['project_id' => $relatedProjectIds],['source' => 1]])
                ->select('id')
                ->column();
        }
        //4. 获取当前用户关联的项目ID（根据实际业务调整关联字段）
        // 假设用户通过Invest_user_map表关联招商项目
        $relatedInvestIds = InvestUserMap::find()
            ->where(['uid' => $uid])
            ->select('project_id')
            ->column();
        // 5. 获取关联招商项目的所有维护记录ID
        $InvestRelatedMaintainIds = [];
        if (!empty($relatedInvestIds)) {
            $InvestRelatedMaintainIds = Maintain::find()
                ->where(['and',['project_id' => $relatedInvestIds],['source' => 2]])
                ->select('id')
                ->column();
        }
        // 6. 合并三类记录ID并去重
        $allMaintainIds = array_unique(array_merge(
            $personalMaintainIds,
            $projectRelatedMaintainIds,
            $InvestRelatedMaintainIds
        ));
        // 构建查询
        $model = Maintain::find()->alias('maintain')
            ->where(['maintain.id' => $allMaintainIds]); // 核心条件：仅查询有权限的记录

        //$model = MaintainUserMap::find()->alias('userMap');
        $model->limit(System::EXPORT_EXCEL_NUM);
        //$model->leftJoin(Maintain::tableName() . 'maintain', 'userMap.maintain_id=maintain.id');
        //$model->select('maintain.*');
        //$model->where('userMap.uid=:uid', ['uid' => $this->session['uid']]);

        if ($projectName) {
            $model->andWhere(['like', 'maintain.project_name', $projectName]);
        }
        if ($parterName) {
            $model->andWhere('maintain.username=:parterName', ['parterName' => $parterName]);
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
