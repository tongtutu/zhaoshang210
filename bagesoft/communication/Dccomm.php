<?php
/**
 * DC 通讯
 *
 * 特殊通讯通过  dccomm 自定义类处理
 *
 * @author: shuguang < 5565907@qq.com >
 * @date: 2023/05/17 10:56:46
 * @lastEditTime: 2023/06/30 13:45:06
 */

namespace bagesoft\communication;

use bagesoft\communication\base\DccommBase;

class Dccomm extends DccommBase
{
    public function run($data = [])
    {
        try {
            return parent::publish($data);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
