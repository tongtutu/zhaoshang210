<?php
/**
 * IP地址库
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 * 项目地址
 * https://github.com/zhuzhichao/ip-location-zh
 * 
 */

namespace bagesoft\library\ipRegion;

use yii;

class Ipip
{
    private static $ip = null;
    private static $fp = null;
    private static $offset = null;
    private static $index = null;
    private static $cached = [];

    public static function find($ip = null)
    {
        try {
            if (empty($ip)) {
                $ip = Yii::$app->request->userIP;
            }

            $nip = gethostbyname($ip);
            $ipdot = explode('.', $nip);
            if ($ipdot[0] < 0 || $ipdot[0] > 255 || count($ipdot) !== 4) {
                return 'N/A';
            }

            if (isset(self::$cached[$nip]) === true) {
                return self::_format(self::$cached[$nip]);
            }

            if (self::$fp === null) {
                self::init();
            }

            $nip2 = pack('N', ip2long($nip));
            $tmp_offset = (int) $ipdot[0] * 4;
            $start = unpack('Vlen', self::$index[$tmp_offset] . self::$index[$tmp_offset + 1] . self::$index[$tmp_offset + 2] . self::$index[$tmp_offset + 3]);
            $index_offset = $index_length = null;
            $max_comp_len = self::$offset['len'] - 1024 - 4;
            for ($start = $start['len'] * 8 + 1024; $start < $max_comp_len; $start += 8) {
                if (self::$index{$start} . self::$index{$start + 1} . self::$index{$start + 2} . self::$index{$start + 3} >= $nip2) {
                    $index_offset = unpack('Vlen', self::$index{$start + 4} . self::$index{$start + 5} . self::$index{$start + 6} . "\x0");
                    $index_length = unpack('Clen', self::$index{$start + 7});
                    break;
                }
            }
            if ($index_offset === null) {
                return 'N/A';
            }

            fseek(self::$fp, self::$offset['len'] + $index_offset['len'] - 1024);
            self::$cached[$nip] = explode("\t", fread(self::$fp, $index_length['len']));
            return self::_format(self::$cached[$nip]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private static function init()
    {
        if (self::$fp === null) {
            self::$ip = new self();
            self::$fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . '17monipdb' . DIRECTORY_SEPARATOR . '17monipdb.dat', 'rb');
            if (self::$fp === false) {
                throw new \Exception('无效的IP数据库文件!');
            }

            self::$offset = unpack('Nlen', fread(self::$fp, 4));
            if (self::$offset['len'] < 4) {
                throw new \Exception('无效的IP数据库文件!');
            }

            self::$index = fread(self::$fp, self::$offset['len'] - 4);
        }
    }

    public function __destruct()
    {
        if (self::$fp !== null) {
            fclose(self::$fp);
        }

    }

    /**
     * 格式化输出结果
     * @param  string $result 行数据
     * @return array
     */
    private static function _format($result)
    {
        return [
            'country' => $result[0],
            'province' => $result[1],
            'city' => $result[2],
            'company' => '',
        ];
    }
}
