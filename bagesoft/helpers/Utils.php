<?php
/**
 * 工具助手
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 */

namespace bagesoft\helpers;

use bagesoft\library\SnowFlake;
use Yii;
use yii\helpers\Json;

class Utils
{
    public function getImgs($content, $order = 'ALL')
    {
        $pattern = "/<img .*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern, $content, $match);
        if (isset($match[1]) && !empty($match[1])) {
            if ($order === 'ALL') {
                return $match[1];
            }
            if (is_numeric($order) && isset($match[1][$order])) {
                return $match[1][$order];
            }
        }
        return '';
    }
    /**
     * css样式应用
     * @param  mixed $val 值
     * @param  array $css 样式
     * @return string
     */
    public static function css($val, $css, $def = 'default')
    {
        if ($val && $css && array_key_exists($val, $css)) {
            return $css[$val];
        } else {
            return $def;
        }
    }

    /**
     * 小数位截取格式化金额，不四舍五入
     * @param  float    $num  [格式化前的金额]
     * @param  integer  $dist [保留的小数位数]
     * @param  BOOL     $zeroComplete [小数位不够dist时，是否用0补齐]
     * @return [type]        [description]
     */
    public static function numFmt($num = 0, $dist = 2, $zeroComplete = true)
    {

        if (!preg_match('/^(-?\d+)(\.\d+)?$/', $num)) {
            return $num;
        }
        if ($dist > 4) {
            $dist = 4;
        } else if ($dist <= 0) {
            $dist = 0;
        }
        if (!is_bool($zeroComplete)) {
            $zeroComplete = true;
        }
        $newNum = floor($num * pow(10, $dist)) / pow(10, $dist);
        if (!$zeroComplete) {
            //去掉小数末尾的0
            $newNum = self::floatZeroCut($newNum);
            $pos = strpos(strval($newNum), '.'); //获取小数点位置
            if (!$pos) {
                //如果没找到
                $dist = 0;
            } else {
                $dist = strlen(strval($newNum)) - $pos - 1;
            }
        }
        $result = number_format($newNum, $dist, '.', '');
        return $result;
    }

    /**
     * 自动去掉小数末尾的0
     * @param  float  $num [小数]
     * @return float       [返回去掉小数末尾0的小数]
     */
    public static function floatZeroCut($num = 0.00)
    {
        if (!preg_match('/^(-?\d+)(\.\d+)?$/', $num)) {
            return '参数错误';
        }
        if ((int) ($num) == $num) {
            return $num;
        }
        $strNum = strval($num);
        if (substr($num, -1) == '0') {
            $strNum = substr($strNum, 0, strlen($num) - 1);
            self::floatZeroCut(floatval($strNum));
        } else {
            return floatval($strNum);
        }
    }

    /**
     * 金额格式化
     * @param  float  $number     金额
     * @param  boolean $fractional 是否使用逗号分隔
     * @param  string  $dot 分隔符
     * @return
     */
    public static function moneyFmt($number, $fractional = false, $dot = '')
    {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1' . $dot . '$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    }

    /**
     * 隐藏字符
     * @param  string $str 输入字符
     * @return string
     */
    public static function hideStr($str)
    {
        if (strpos($str, '@')) {
            $email_array = explode("@", $str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $rs = $prevfix . $str;
        } else {
            $pattern = '/(1[1-9]{1}[0-9])[0-9]{4}([0-9]{4})/i';
            if (preg_match($pattern, $str)) {
                $rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4);
            } else {
                $rs = substr($str, 0, 3) . "***" . substr($str, -1);
            }
        }
        return $rs;
    }

    /**
     * 对象转数组
     * @param  object $obj 对象
     * @return object
     */
    public static function object2array($obj)
    {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if (is_array($arr)) {
            return array_map(array(__CLASS__, 'object2array'), $arr);
        } else {
            return $arr;
        }
    }

    /**
     * 数组转对象
     * @param  array $arr 数组
     * @return object
     */
    public static function array2object($arr)
    {
        if (is_array($arr)) {
            return (object) array_map(array(__CLASS__, 'array2object'), $arr);
        } else {
            return $arr;
        }
    }

    /**
     * 字符缩短
     * 将特定字符格式化为短字符串
     *
     * @param  string  $str 输入字符
     * @param  integer $pos 字符获取
     * @return string|array
     */
    public static function shorten($str, $pos = 0)
    {
        $base32 = [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
        ];
        $hex = md5($str);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        $output = [];
        for ($i = 0; $i < $subHexLen; $i++) {
            $subHex = substr($hex, $i * 8, 8);
            $int = 0x3FFFFFFF & (1 * ('0x' . $subHex));
            $out = '';
            for ($j = 0; $j < 6; $j++) {
                $val = 0x0000001F & $int;
                $out .= $base32[$val];
                $int = $int >> 5;
            }
            $output[] = $out;
        }
        if ($pos < 0 || $pos > 3) {
            return $output;
        } else {
            return $output[$pos];
        }
    }

    /**
     * 计算字符串绝对长度
     * @param  string $str
     * @return int
     */
    public static function absLen($str)
    {
        preg_match_all("/./us", $str, $matches);
        return count(current($matches));
    }

    /**
     * 检测是否Json合法
     * @param  string  $str 字符
     * @return boolean
     */
    public static function isJson($str)
    {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * 数组转JSON
     *
     * @param mixed $data
     * @return string
     */
    public static function jsonStr($data)
    {
        if (is_array($data)) {
            return Json::encode($data);
        } elseif (is_object($data)) {
            return Json::encode(self::object2array($data));
        } elseif ($data) {
            return $data;
        } else {
            return '[]';
        }
    }

    /**
     * JSON转数组
     *
     * @param string $data
     * @return array
     */
    public static function jsonArr($data)
    {
        if (is_array($data)) {
            return $data;
        } elseif (self::isJson($data)) {
            return Json::decode($data);
        } else {
            return [];
        }
    }

    /**
     * 计算一个随机浮点数
     * @param  integer $min     [最小值]
     * @param  integer $max     [最大值]
     * @param  boolean $decimal [保留小数]
     * @return [type]           [浮点数]
     */
    public static function randomFloat($min = 0, $max = 1, $decimal = false)
    {
        $number = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        if ($decimal) {
            return sprintf("%." . $decimal . "f", $number);
        } else {
            return $number;
        }
    }

    /**
     * 时间戳转星期
     * @param  [type] $timestamp [时间戳]
     * @return [type]            [星期]
     */
    public static function weekday($timestamp)
    {
        if (is_numeric($timestamp)) {
            $weekday = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
            return $weekday[date('w', $timestamp)];
        } else {
            return '';
        }
    }

    /**
     * 数组格式化
     * 只支持一维数组
     * @param  array $original  原始数组
     * @param  array  $input    输入数据
     * @return array
     */
    public static function arrayFmt($original, $input = [])
    {
        if (!is_array($original)) {
            $original = [];
        }
        if (isset($input['remove'])) {
            foreach ($input['remove'] as $key) {
                unset($original[$key]);
            }
        }
        if (isset($input['add'])) {
            foreach ($input['add'] as $key => $val) {
                $original[$key] = $val;
            }
        }
        return $original;
    }

    /**
     * 验证邮箱
     * @param string $string
     * @param string $enforce
     */
    public static function isEmail($string, $enforce = true)
    {
        if (empty($string) && $enforce = false) {
            return true;
        }

        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($string, '@') !== false && strpos($string, '.') !== false) {
            if (preg_match($chars, $string)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * 验证手机号码
     * @param string $str
     * @param boolean $enforce
     * @return boolean|number
     */
    public static function isMobile($string, $enforce = true)
    {
        if (empty($string) && $enforce = false) {
            return true;
        }
        return preg_match('#^13[\d]{9}$|14^[0-9]\d{8}|^15[0-9]\d{8}$|^18[0-9]\d{8}$#', $string);
    }

    /**
     * 验证固定电话
     * @param string $str
     * @param string $enforce
     * @return boolean|number
     */
    public static function isTel($string, $enforce = true)
    {
        if (empty($string) && $enforce = false) {
            return true;
        }
        return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', trim($string));
    }

    /**
     * 验证qq号码
     * @param string $string
     * @param string $enforce
     * @return boolean|number
     */
    public static function isQq($string, $enforce = true)
    {
        if (empty($string) && $enforce == false) {
            return true;
        }
        return preg_match('/^[1-9]\d{4,12}$/', trim($string));
    }

    /**
     * 验证邮政编码
     * @param string $string
     * @param string $enforce
     * @return boolean|number
     */
    public static function isZipCode($string, $enforce = true)
    {
        if (empty($string) && $enforce == false) {
            return true;
        }
        return preg_match('/^[1-9]\d{5}$/', trim($string));
    }

    /**
     * 验证身份证
     * @param string $idcard
     * @return boolean|string
     */
    public static function isIdCard($idcard)
    {
        if (empty($idcard)) {
            return false;
        }
        $city = [11 => "北京", 12 => "天津", 13 => "河北", 14 => "山西", 15 => "内蒙古", 21 => "辽宁", 22 => "吉林", 23 => "黑龙江", 31 => "上海", 32 => "江苏", 33 => "浙江", 34 => "安徽", 35 => "福建", 36 => "江西", 37 => "山东", 41 => "河南", 42 => "湖北", 43 => "湖南", 44 => "广东", 45 => "广西", 46 => "海南", 50 => "重庆", 51 => "四川", 52 => "贵州", 53 => "云南", 54 => "西藏", 61 => "陕西", 62 => "甘肃", 63 => "青海", 64 => "宁夏", 65 => "新疆", 71 => "台湾", 81 => "香港", 82 => "澳门", 91 => "国外"];
        $idCardLength = strlen($idcard);
        //长度验证
        if (!preg_match('/^\d{17}(\d|x)$/i', $idcard) and !preg_match('/^\d{15}$/i', $idcard)) {
            return false;
        }
        //地区验证
        if (!array_key_exists(intval(substr($idcard, 0, 2)), $city)) {
            return false;
        }
        // 15位身份证验证生日，转换为18位
        if ($idCardLength == 15) {
            $sBirthday = '19' . substr($idcard, 6, 2) . '-' . substr($idcard, 8, 2) . '-' . substr($idcard, 10, 2);
            $dd = date('Y-m-d', strtotime($sBirthday));
            if ($sBirthday != $dd) {
                return false;
            }

            $idcard = substr($idcard, 0, 6) . "19" . substr($idcard, 6, 9); //15to18
            $bit18 = self::getVerifyBit($idcard); //算出第18位校验码
            $idcard = $idcard . $bit18;
        }
        // 判断是否大于2078年，小于1900年
        $year = substr($idcard, 6, 4);
        if ($year < 1900 || $year > 2078) {
            return false;
        }
        //18位身份证处理
        $sBirthday = substr($idcard, 6, 4) . '-' . substr($idcard, 10, 2) . '-' . substr($idcard, 12, 2);
        $dd = date('Y-m-d', strtotime($sBirthday));
        if ($sBirthday != $dd) {
            return false;
        }
        //身份证编码规范验证
        $idcardBase = substr($idcard, 0, 17);
        if (strtoupper(substr($idcard, 17, 1)) != self::getVerifyBit($idcardBase)) {
            return false;
        }
        return true;
    }

    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     * @param string $idcard
     */
    public static function getVerifyBit($idcard)
    {
        if (strlen($idcard) != 17) {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard); $i++) {
            $checksum += substr($idcard, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

    /**
     * 字符截取
     *
     * @param $string
     * @param $length
     * @param $dot
     */
    public static function cutStr($string, $length, $dot = '...', $charset = 'utf-8')
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

        $strcut = '';
        if (strtolower($charset) == 'utf-8') {

            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {

                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }

                if ($noc >= $length) {
                    break;
                }

            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);

        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }

        $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

        $pos = strrpos($strcut, chr(1));
        if ($pos !== false) {
            $strcut = substr($strcut, 0, $pos);
        }

        return $strcut . $dot;
    }

    /**
     * 描述格式化
     * @param  $string
     */
    public static function clsDesc($string, $length = 0, $dot = '...', $charset = 'utf-8')
    {
        if ($length) {
            return Utils::cutstr(strip_tags(str_replace(["\r\n", "\t", '&nbsp;'], '', trim($string))), $length, $dot, $charset);
        } else {
            return strip_tags(str_replace(["\r\n", "\t", '&nbsp;'], '', trim($string)));
        }
    }

    /**
     * 检测是否为英文或英文数字的组合
     *
     * @return boolean
     */
    public static function isEnglist($string)
    {
        if (!preg_match("/^[A-Z0-9]{1,26}$/i", $string)) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * http补全
     * @param string $url
     * @return string|unknown
     */
    public static function httpPre($url)
    {
        if (empty($url)) {
            return '';
        } elseif (!preg_match("/^(http|https|ftp):/", $url)) {
            return 'http://' . $url;
        } else {
            return $url;
        }

    }

    /**
     * 检测字符集
     * @param  string $string 待检测字符
     * @return string
     */
    public static function testCharset($string)
    {
        return mb_detect_encoding($string, ['ASCII', 'GB2312', 'GBK', 'UTF-8']);
    }

    /**
     * 自动转换字符集 支持数组转换
     * @param string $string
     * @param string $from
     * @param string $to
     */
    public static function autoCharset($string, $from = 'gbk', $to = 'utf-8')
    {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($string) || (is_scalar($string) && !is_string($string))) {
            //如果编码相同或者非字符串标量则不转换
            return $string;
        }
        if (is_string($string)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $string);
            } else {
                return $string;
            }
        } elseif (is_array($string)) {
            foreach ($string as $key => $val) {
                $_key = self::autoCharset($key, $from, $to);
                $string[$_key] = self::autoCharset($val, $from, $to);
                if ($key != $_key) {
                    unset($string[$key]);
                }

            }
            return $string;
        } else {
            return $string;
        }
    }

    /**
     * 反引用一个引用字符串
     * @param  $string
     * @return string
     */
    public static function stripslashes($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = self::stripslashes($val);
            }

        } else {
            $string = stripslashes($string);
        }
        return $string;
    }

    /**
     * 引用字符串
     * @param  $string
     * @param  $force
     * @return string
     */
    public static function addslashes($string, $force = 1)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = self::addslashes($val, $force);
            }

        } else {
            $string = addslashes($string);
        }
        return $string;
    }

    /**
     * GUID
     * @param string $opt 是否保留  {}
     */
    public static function guid($opt = true)
    {
        if (function_exists('com_create_guid')) {
            if ($opt) {
                return com_create_guid();
            } else {
                return trim(com_create_guid(), '{}');
            }
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $left_curly = $opt ? chr(123) : '';
            $right_curly = $opt ? chr(125) : '';
            $uuid = $left_curly
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . $right_curly;
            return $uuid;
        }
    }

    /**
     * 随机字符
     * @param integer $len 长度
     * @param string $type 类型
     * @param string $addChars 追加字符
     * @return string
     */
    public static function randStr($len = 6, $type = '', $addChars = '')
    {
        $string = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 4:
                $chars = 'abcdefghijkmnpqrstuvwxyz0123456789' . $addChars;
                break;
            case 5:
                $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ0123456789' . $addChars;
                break;
            default:
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) {
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }

        $chars = str_shuffle($chars);
        $string = substr($chars, 0, $len);
        return $string;
    }

    /**
     * 对字符串执行 ROT13 转换/反转换
     * @param string $s
     * @param number $n
     */
    public static function strRot($s, $n = 13)
    {
        $n = (int) $n % 26;
        if (!$n) {
            return $s;
        }

        for ($i = 0, $l = strlen($s); $i < $l; $i++) {
            $c = ord($s[$i]);
            if ($c >= 97 && $c <= 122) {
                $s[$i] = chr(($c - 71 + $n) % 26 + 97);
            } else if ($c >= 65 && $c <= 90) {
                $s[$i] = chr(($c - 39 + $n) % 26 + 65);
            }
        }
        return $s;
    }

    /**
     * 浏览器友好的变量输出
     * @param mixed         $var 变量
     * @param boolean       $echo 是否输出 默认为true 如果为false 则返回输出字符串
     * @param string        $label 标签 默认为空
     * @param integer       $flags htmlspecialchars flags
     * @return void|string
     */
    public static function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE)
    {
        $label = (null === $label) ? '' : rtrim($label) . ':';
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);

        if (!extension_loaded('xdebug')) {
            $output = htmlspecialchars($output, $flags);
        }
        $output = '<pre>' . $label . $output . '</pre>';

        if ($echo) {
            echo ($output);
            return null;
        } else {
            return $output;
        }
    }

    /**
     * 格式化单位
     * @param unknown $size
     */
    public static function byteFmt($size)
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * 下拉框，单选按钮 自动选择
     * @param string $string
     * @param number $param
     * @param string $type
     */
    public static function selected($string, $param = '', $type = 'select')
    {
        if (is_array($param)) {
            $true = in_array($string, $param);
        } elseif ($string == $param) {
            $true = true;
        }

        if ($true) {
            $return = $type == 'select' ? 'selected="selected"' : 'checked="checked"';
        }

        echo $return;
    }

    /**
     * base64_encode
     * @param string $string
     * @return string|mixed
     */
    public static function base64en($string)
    {
        if (empty($string)) {
            return '';
        }

        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    /**
     * base64_decode
     * @param string $string
     * @return string
     */
    public static function base64de($string)
    {
        if (empty($string)) {
            return '';
        }

        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }

    /**
     * 验证网址
     * @param string $string
     */
    public static function url($string, $enforce = true)
    {
        if (empty($string) && $enforce == false) {
            return true;
        }

        return preg_match('#(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?#i', $string) ? true : false;
    }

    /**
     * 拆分sql
     *
     * @param $sql
     */
    public static function splitsql($sql)
    {
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=" . Yii::$app->db->charset, $sql);
        $sql = str_replace("\r", "\n", $sql);
        $ret = [];
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-') {
                    $ret[$num] .= $query;
                }

            }
            $num++;
        }
        return ($ret);
    }

    /**
     * 时间戳格式化
     *
     * @param int $timestamp 时间
     * @param string $type 显示类型 short：短格式 (x[秒|分|小时|天|年]前) ,long：长格式 (x小时x分钟前)
     * @return string
     */
    public static function timeFmt($timestamp, $type = 'short')
    {
        if ($timestamp <= 0) {
            return '-';
        }
        if ($type == 'short') {
            return Yii::$app->formatter->asRelativeTime($timestamp);
        }

        $formats = array(
            'DAY' => '%s天前',
            'DAY_HOUR' => '%s天%s小时前',
            'HOUR' => '%s小时',
            'HOUR_MINUTE' => '%s小时%s分前',
            'MINUTE' => '%s分钟前',
            'MINUTE_SECOND' => '%s分钟%s秒前',
            'SECOND' => '%s秒前',
        );
        /* 计算出时间差 */
        $nowTime = time();
        if ($timestamp > $nowTime) {
            return '';
        }

        $seconds = $nowTime - $timestamp;
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);

        if ($days > 0 && $days < 31) {
            $diffFormat = 'DAY';
        } elseif ($days == 0) {
            $diffFormat = ($hours > 0) ? 'HOUR' : 'MINUTE';
            if ($diffFormat == 'HOUR') {
                $diffFormat .= ($minutes > 0 && ($minutes - $hours * 60) > 0) ? '_MINUTE' : '';
            } else {
                $diffFormat = (($seconds - $minutes * 60) > 0 && $minutes > 0) ? $diffFormat . '_SECOND' : 'SECOND';
            }
        } else {
            $diffFormat = 'TURE_DATE_TIME'; //超出30天, 正常时间显示
        }

        $dateDiff = null;
        switch ($diffFormat) {
            case 'DAY':
                $dateDiff = sprintf($formats[$diffFormat], $days);
                break;
            case 'DAY_HOUR':
                $dateDiff = sprintf($formats[$diffFormat], $days, $hours - $days * 60);
                break;
            case 'HOUR':
                $dateDiff = sprintf($formats[$diffFormat], $hours);
                break;
            case 'HOUR_MINUTE':
                $dateDiff = sprintf($formats[$diffFormat], $hours, $minutes - $hours * 60);
                break;
            case 'MINUTE':
                $dateDiff = sprintf($formats[$diffFormat], $minutes);
                break;
            case 'MINUTE_SECOND':
                $dateDiff = sprintf($formats[$diffFormat], $minutes, $seconds - $minutes * 60);
                break;
            case 'SECOND':
                $dateDiff = sprintf($formats[$diffFormat], $seconds);
                break;
            default:
                $dateDiff = date('y-m-d H:i', $timestamp);
        }
        return $dateDiff;
    }

    /**
     * ip格式化
     * @param  [type] $ip [description]
     * @return [type]     [description]
     */
    public static function ipFmt($ip, $len = 1)
    {
        if (empty($ip)) {
            return;
        }
        $reg1 = '/((?:\d+\.){3})\d+/';
        $reg2 = '~(\d+)\.(\d+)\.(\d+)\.(\d+)~';
        if ($len == 1) {
            return preg_replace($reg1, "\\1*", $ip);
        } else {
            return preg_replace($reg2, "$1.$2.*.*", $ip);
        }
    }

    /**
     * 保存base64编码图片内容
     * @param  [type] $base64 [description]
     * @return [type]         [description]
     */
    public static function base64Str2Img($base64)
    {
        $imgStr = preg_replace('/data:image\/[a-zA-Z]+;base64,/i', '', $base64Str);
        $imgStr = base64_decode($imgStr);
        $filepath = 'uploads/' . date('Ym') . '/';
        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filename = md5(time() . rand(1, 99999)) . '.jpg';
        $pathname = $filepath . $filename;
        file_put_contents($pathname, $imgStr);
        return $pathname;
    }

    /**
     * 计算两个日期相隔多少年，多少月，多少天
     * param string $date1[格式如：2011-11-5]
     * param string $date2[格式如：2012-12-01]
     * return array array('year','month','day');
     */
    public static function diffDate($date1, $date2)
    {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        $year = $interval->format('%y');
        $month = $interval->format('%m');
        if ($year > 0) {
            $month = $month + $year * 12;
        }
        return [
            'year' => $year,
            'month' => $month,
            'monthAdd' => $month + 1,
            'day' => $interval->format('%a'),
        ];
    }

    /**
     * 隐藏手机号
     *
     * @param integer $phone
     */
    public static function hidePhone($phone)
    {
        // 检查输入的手机号是否合法
        if (preg_match('/^\d{11}$/', $phone)) {
            // 隐藏手机号中间的四位数字
            $hidden = substr($phone, 0, 3) . '****' . substr($phone, -4);
            return $hidden;
        } else {
            return '**';
        }
    }

    /**
     * 隐藏姓名
     *
     * @param string $name
     * @return 
     */
    public static function hideName($name)
    {
        $nameLength = mb_strlen($name, 'utf-8');
        switch ($nameLength) {
            case 1:
                return $name . '*';
            case 2:
                return mb_substr($name, 0, 1, 'utf-8') . '*';
            case 3:
                return mb_substr($name, 0, 1, 'utf-8') . '**';
            case 4:
                return mb_substr($name, 0, 2, 'utf-8') . '**';
            default:
                return mb_substr($name, 0, 3, 'utf-8') . str_repeat('*', $nameLength - 3);
        }
    }

    /**
     * 获取索引ID
     * @return string
     */
    public static function indexId()
    {
        $snowFlake = SnowFlake::getInstance();
        return (string) $snowFlake->nextId();
    }

    /**
     * 格式化日期范围
     * 
     * @param mixed $date
     * @return array
     */
    public static function dateRange($dateRange)
    {
        // 分割日期范围字符串
        $dates = explode(' - ', $dateRange);

        // 初始化时间戳
        $startTimestamp = null;
        $endTimestamp = null;

        // 处理起始日期
        if (!empty($dates[0])) {
            $startTimestamp = strtotime($dates[0]);
            // 将时间戳设为当天的 00:00:00
            $startTimestamp = strtotime('midnight', $startTimestamp);
        }

        // 处理结束日期
        if (!empty($dates[1])) {
            $endTimestamp = strtotime($dates[1]);
            // 将时间戳设为当天的 23:59:59
            $endTimestamp = strtotime('23:59:59', $endTimestamp);
        }

        return ['start' => $startTimestamp, 'end' => $endTimestamp];
    }

    /**
     * 获取模型错误
     * @param mixed $model
     * @return string
     */
    public static function getModelErrors($model)
    {
        $errors = [];
        foreach ($model->getErrors() as $messages) {
            $errors = array_merge($errors, $messages);
        }
        return implode("\n", $errors);
    }
}
