<?php
/**
 * Send
 * @author        shuguang <5565907@qq.com>
 * @date          2020-11-10 15:25
 */

namespace bagesoft\constant;

class SendConst
{
    /**
     * 发送渠道
     */

    const CHANNEL_SMS = 1; //渠道_短信
    const CHANNEL_WECHAT = 2; //渠道_微信
    const CHANNEL_EMAIL = 3; //渠道_邮件
    const CHANNEL_JPUSH = 4; //渠道_极光
    const CHANNEL = [
        '0' => '--',
        self::CHANNEL_SMS => '短信',
        self::CHANNEL_WECHAT => '微信',
        self::CHANNEL_EMAIL => '邮件',
        self::CHANNEL_JPUSH => '极光',
    ];
}
