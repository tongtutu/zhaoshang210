<?php
/**
 * MqConst
 * @author: shuguang < 5565907@qq.com >
 * @date: 2020/11/09 14:35:04
 * @lastEditTime: 2025/03/01 22:24:36
 */

namespace bagesoft\constant;

class MessageQueueConst
{
    const WEB_TO_DC = 'web2dc'; //节点服务器名_WEB到DC

    const DC_ASYN_QUEUE_NAME = 'asyn.task'; //DC通信队列名
    const DC_ASYN_EXCHANGE_NAME = 'asyn.task'; //DC交换机名
    const DC_ASYN_ROUTE_NAME = 'asyn.task'; //DC路由名

    const USER_PASSWORD_RESET = 'user.password.reset'; //用户密码重置
    const USER_PASSWORD_FORGOT = 'user.password.forgot'; //用户密码忘记
    const USER_EDIT_MOBILE = 'user.edit.mobile'; //用户修改手机号
    const USER_CREATE = 'user.create'; //用户创建
    const USER_LOGIN = 'user.login'; //用户登录

    const TIMER_REMIND_MAINTAIN_UPDATE = 'timer.remind.maintain.update'; //定时提醒维护更新
    const TIMER_REMIND_DEMAND_WORKS_UPLOAD = 'timer.remind.demand.works.upload'; //定时提醒需求响应

    const DEMAND_ACCEPT = 'demand.accept'; //需求接受
    const DEMAND_REJECT = 'demand.reject'; //需求拒绝
    const DEMAND_DELETE = 'demand.delete'; //需求删除
    const DEMAND_CREATE = 'demand.create'; //需求创建
    const DEMAND_ASSIGN = 'demand.assign'; //需求分配
    const DEMAND_ASSIGN_FOR_WORKER = 'demand.assign.for.worker'; //需求分配给创作者
    const DEMAND_WORKS_UPLOAD = 'demand.works.upload'; //需求作品上传
    const DEMAND_WORKS_REJECT = 'demand.works.reject'; //需求作品拒绝
    const DEMAND_WORKS_PASS = 'demand.works.pass'; //需求作品通过

    const MAINTAIN_CREATE = 'maintain.create'; //更新维护创建
    const MAINTAIN_DELETE = 'maintain.delete'; //更新维护删除

    const PROJECT_CREATE = 'project.create'; //项目创建
    const PROJECT_DELETE = 'project.delete'; //项目删除
    const PROJECT_STEPS_BT_TOADMIN = 'project.steps.bt.toadmin'; //招投标阶段通知管理员

}
