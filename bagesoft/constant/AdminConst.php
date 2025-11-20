<?php
/**
 * admin常量定义
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\constant;

class AdminConst
{

    const ROLE_SYSTEM = 1; //系统
    const ROLE_SUPER = 3; //超级管理
    const ROLE_MANAGER = 5; //项目经理

    const SUPER_UIDS = [2]; //超级管理员uid

    const SUPER_GIDS = [
        self::ROLE_SUPER,
        self::ROLE_SYSTEM,
    ];
}
