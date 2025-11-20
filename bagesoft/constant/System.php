<?php
/**
 * 系统常量
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @link          http://www.cookman.cn
 */
namespace bagesoft\constant;

class System
{

    const EXPORT_EXCEL_NUM = 600; //导出excel最大行数
    //来源
    const SOURCE_CUSTOMER = 1;
    const SOURCE_INVEST = 2;
    const SOURCE = [
        self::SOURCE_CUSTOMER => '市场信息',
        self::SOURCE_INVEST => '招商信息',
    ];

    //来源
    const SOURCE_APP_ADMIN = 1;
    const SOURCE_APP_USER = 2;
    const SOURCE_APP = [
        self::SOURCE_APP_ADMIN => '管理',
        self::SOURCE_APP_USER => '会员',
    ];

    //性别
    const SEX = [
        1 => '男',
        2 => '女',
        0 => '未知',
    ];

    //系统类型
    const CATS_KEYID_TAGS = 'tags';
    const CATS_KEYID_PROJECT = 'project';
    const CATS_KEYID_TAGS_TXT = '项目标签';
    const CATS_KEYID_PROJECT_TXT = '招商所属项目';

    const CATS = [
        self::CATS_KEYID_TAGS => self::CATS_KEYID_TAGS_TXT,
        self::CATS_KEYID_PROJECT => self::CATS_KEYID_PROJECT_TXT,
    ];

    //手机验证码
    const HASH_CODE_LOGIN = 'login'; //登录
    const HASH_CODE_EDIT_PASS = 'user.password.edit'; //修改密码
    const HASH_CODE_RESET_PASS = 'user.password.reset'; //重置密码
    const HASH_CODE_EDIT_MOBILE = 'user.edit.mobile'; //修改手机号

    const ALLOW_LOGIN_FAIL_NUM = 10; //允许最大失败次数

    //用户角色
    const USER_ROLE_APP = 1; //员工
    const USER_ROLE_ADMIN = 2; //管理员

    //信息角色
    const OWNER = 1; //所有者
    const PARTNER = 2; //伙伴
    const WORKER = 3; //创作者
    const MANAGER = 4; //经理
    const BT_MANAGER = 5; //招投标经理

    const APPROVE = 1; //审核
    const REJECT = 2; //驳回

    const MESSAGE_READ = 1; //已读
    const MESSAGE_UNREAD = 2; //未读

    const DELETE_LEVEL_1 = 1; //正常
    const DELETE_LEVEL_2 = 2; //删除2级
    const DELETE_LEVEL_3 = 3; //删除3级

    const STARS = [
        1 => '⭐',
        2 => '⭐⭐',
        3 => '⭐️⭐️⭐️',
        4 => '⭐️⭐️⭐️⭐️',
        5 => '⭐️⭐️⭐️⭐️⭐️',
    ];

    const MESSAGE_CHANNEL_SMS = 1; //短信
    const MESSAGE_CHANNEL_EMAIL = 2; //邮件
    const MESSAGE_CHANNEL_WECHAT = 3; //微信

    const MESSAGE_CHANNEL = [
        0 => '--',
        self::MESSAGE_CHANNEL_SMS => '短信',
        self::MESSAGE_CHANNEL_EMAIL => '邮件',
        self::MESSAGE_CHANNEL_WECHAT => '微信',
    ];

    const ALLOW_UPLOAD_FILE_TYPE = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar']; //允许上传的文件类型

    const UPLOAD_SOURCE_CUSTOMER = 1; //来自客户
    const UPLOAD_SOURCE_INVEST = 2; //来自招商
    const UPLOAD_SOURCE_DEMAND = 3; //来自需求
    const UPLOAD_SOURCE_MAINTAIN = 4; //来自跟进
    const UPLOAD_SOURCE_WORKS = 5; //来自创作
    const UPLOAD_SOURCE_WORKS_AUDIT = 6; //创作审核

    const YES = 1; //是
    const NO = 2; //否
    const NORMAL = 0;
    const STATUS = [
        self::YES => '是',
        self::NO => '否',
    ];

}
