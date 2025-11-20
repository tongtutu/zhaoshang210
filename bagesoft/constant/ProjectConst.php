<?php
/**
 * 项目
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */

namespace bagesoft\constant;

class ProjectConst
{
    //项目阶段
    public const STEPS_1 = 1;
    public const STEPS_2 = 2;
    public const STEPS_3 = 3;
    public const STEPS_4 = 4;
    public const STEPS_5 = 5;
    public const STEPS_6 = 6;
    public const STEPS_7 = 7;
    public const STEPS_8 = 8;
    public const STEPS_9 = 9;
    public const STEPS_10 = 10;
    public const STEPS_11 = 11;
    public const STEPS_12 = 12;
    public const STEPS = [
        self::STEPS_1 => '初期建设阶段',
        self::STEPS_2 => '建设投入阶段',
        self::STEPS_3 => '成熟阶段',
        self::STEPS_4 => '投标阶段',
        self::STEPS_5 => '已确认投标任务',
        self::STEPS_6 => '已投标',
        self::STEPS_7 => '已中标',
        self::STEPS_8 => '初步洽谈',
        self::STEPS_9 => '意向确定',
        self::STEPS_10 => '合作带看',
        self::STEPS_11 => '协议签订',
        self::STEPS_12 => '交房入驻',
    ];

    //项目阶段 - 招商
    public const STEPS_INVEST_1 = 1;
    public const STEPS_INVEST_2 = 2;
    public const STEPS_INVEST_3 = 3;
    public const STEPS_INVEST_4 = 4;
    public const STEPS_INVEST_5 = 5;
    public const STEPS_INVEST = [
        self::STEPS_INVEST_1 => '初步洽谈',
        self::STEPS_INVEST_2 => '意向确定',
        self::STEPS_INVEST_3 => '合作带看',
        self::STEPS_INVEST_4 => '协议签订',
        self::STEPS_INVEST_5 => '交房入驻',
    ];

    public const RECYCLE_YES = 1;
    public const RECYCLE_NO = 2;

    //需求角色
    public const DEMAND_ROLE_1 = 1; //需求方
    public const DEMAND_ROLE_2 = 2; //响应方

    //需求
    //作品审核状态 - 信息所有者
    public const DEMAND_STATUS_1 = 1;
    public const DEMAND_STATUS_2 = 2;
    public const DEMAND_STATUS_3 = 3;
    public const DEMAND_STATUS_WAIT_AUDIT = 96;
    public const DEMAND_STATUS_WAIT_PARTNER = 97;
    public const DEMAND_STATUS_WAIT_WORKS = 98;
    public const DEMAND_STATUS_N = 99;
    public const DEMAND_STATUS_SUCCESS = 100;

    public const DEMAND_STATUS = [
        self::DEMAND_STATUS_1 => '首轮反馈',
        self::DEMAND_STATUS_2 => '二轮反馈',
        self::DEMAND_STATUS_3 => '三轮反馈',
        self::DEMAND_STATUS_N => 'N轮反馈',
        self::DEMAND_STATUS_WAIT_AUDIT => '待审核作品',
        self::DEMAND_STATUS_WAIT_WORKS => '待提交作品',
        self::DEMAND_STATUS_WAIT_PARTNER => '待分配合作伙伴',
        self::DEMAND_STATUS_SUCCESS => '已完成',
    ];

    //需求响应状态 - 合作伙伴
    public const DEMAND_WORKS_UPLOAD_1 = 1;
    public const DEMAND_WORKS_UPLOAD_2 = 2;
    public const DEMAND_WORKS_UPLOAD_3 = 3;
    public const DEMAND_WORKS_UPLOAD_N = 99;

    public const DEMAND_WORKS_UPLOAD = [
        self::DEMAND_WORKS_UPLOAD_1 => '首次提交',
        self::DEMAND_WORKS_UPLOAD_2 => '二次提交',
        self::DEMAND_WORKS_UPLOAD_3 => '三次提交',
        self::DEMAND_WORKS_UPLOAD_N => 'N次提交',
    ];

    //需求申请状态 - 信息所有者
    public const DEMAND_APPLY_SUBMIT = 1;
    public const DEMAND_APPLY_WAIT = 2;
    public const DEMAND_APPLY = [
        self::DEMAND_APPLY_SUBMIT => '已申请',
        self::DEMAND_APPLY_WAIT => '未申请',
    ];

    //作品审核 - 信息所有者
    public const DEMAND_WORKS_AUDIT_PASS = 1;
    public const DEMAND_WORKS_AUDIT_WAIT = 2;
    public const DEMAND_WORKS_AUDIT_REJECT = 3;
    public const DEMAND_WORKS_AUDIT = [
        self::DEMAND_WORKS_AUDIT_WAIT => '待审核',
        self::DEMAND_WORKS_AUDIT_PASS => '作品已通过',
        self::DEMAND_WORKS_AUDIT_REJECT => '退回修改',
    ];

    public const DEMAND_WORKS_AUDIT_SELECT = [
        self::DEMAND_WORKS_AUDIT_PASS => '已确认通过',
        self::DEMAND_WORKS_AUDIT_REJECT => '退回修改',
    ];

    //创作接受状态 - 合作者
    public const WORKS_ACCEPT_APPROVE = 1; //接受
    public const WORKS_ACCEPT_WAIT = 2; //待接受
    public const WORKS_ACCEPT_REJECT = 3; //拒绝
    public const WORKS_STATUS = [
        self::WORKS_ACCEPT_APPROVE => '已接受创作',
        self::WORKS_ACCEPT_WAIT => '待研究员确认',
        self::WORKS_ACCEPT_REJECT => '谢绝创作',
    ];

    //跟进状态
    public const MAINTAIN_STATUS_PASS = 1;
    public const MAINTAIN_STATUS_WAIT = 2;
    public const MAINTAIN_STATUS_REJECT = 3;
    public const MAINTAIN_STATUS_PASS_TXT = '审核确认';
    public const MAINTAIN_STATUS_WAIT_TXT = '待审核';
    public const MAINTAIN_STATUS_REJECT_TXT = '审核拒绝';
    public const MAINTAIN_STATUS = [
        self::MAINTAIN_STATUS_PASS => self::MAINTAIN_STATUS_PASS_TXT,
        self::MAINTAIN_STATUS_WAIT => self::MAINTAIN_STATUS_WAIT_TXT,
        self::MAINTAIN_STATUS_REJECT => self::MAINTAIN_STATUS_REJECT_TXT,
    ];
    public const MAINTAIN_CHANGE_STATUS = [
        self::MAINTAIN_STATUS_PASS => self::MAINTAIN_STATUS_PASS_TXT,
        self::MAINTAIN_STATUS_REJECT => self::MAINTAIN_STATUS_REJECT_TXT,
    ];

    public const MAINTAIN_REMIND_YES = 1;//需提醒
    public const MAINTAIN_REMIND_NO = 2;//不需要提醒
    public const MAINTAIN_REMIND_READY = 3;//已经提醒

    //维护类型
    public const MAINTAIN_TYPEID_1 = 1;//客户外访
    public const MAINTAIN_TYPEID_2 = 2;//客户带看
    public const MAINTAIN_TYPEID_3 = 3;//市场调研
    public const MAINTAIN_TYPEID_4 = 4;//渠道合作
    public const MAINTAIN_TYPEID_5 = 5;//其他
    public const MAINTAIN_TYPEID_6 = 6;//客户到访
    public const MAINTAIN_TYPEID_7 = 7;//甲方与客户洽谈
    public const MAINTAIN_TYPEID_8 = 8;//项目推荐

    public const MAINTAIN_TYPE = [
        self::MAINTAIN_TYPEID_1 => '客户外访',
        self::MAINTAIN_TYPEID_6 => '客户到访',
        self::MAINTAIN_TYPEID_2 => '客户带看',
        self::MAINTAIN_TYPEID_3 => '市场调研',
        self::MAINTAIN_TYPEID_4 => '渠道合作',
        self::MAINTAIN_TYPEID_8 => '项目推荐',
        self::MAINTAIN_TYPEID_7 => '甲方与客户洽谈',
        self::MAINTAIN_TYPEID_5 => '其他',
    ];

    //隐藏信息类型
    public const HIDE_TYPE_NAME = 1; //姓名
    public const HIDE_TYPE_PHONE = 2; //电话
    public const HIDE_TYPE_ADDR = 3; //地址

    //渠道
    public const CHANNEL_SELF = 1;
    public const CHANNEL_AGENT = 2;
    public const CHANNEL = [
        self::CHANNEL_SELF => '自拓',
        self::CHANNEL_AGENT => '中介',
    ];

    public const PARTNER_ACCEPT_APPOVE = 1;
    public const PARTNER_ACCEPT_WAIT = 2;
    public const PARTNER_ACCEPT_REJECT = 3;
    public const PARTNER_ACCEPT = [
        self::PARTNER_ACCEPT_APPOVE => '合作伙伴已审核',
        self::PARTNER_ACCEPT_WAIT => '合作伙伴审核中',
        self::PARTNER_ACCEPT_REJECT => '合作伙伴拒绝',
    ];

    public const EXPAND_TYPE_AUTO = 1;
    public const EXPAND_TYPE_NON_AUTO = 2;
    public const EXPAND_TYPE = [
        self::EXPAND_TYPE_AUTO => '自主拓展',
        self::EXPAND_TYPE_NON_AUTO => '非自主拓展',
    ];

    public const PROJECT_ASSESS_TAG_ID = 5;//所属考核项目标签id
}
