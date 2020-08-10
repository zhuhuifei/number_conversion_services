<?php
/*
 * @Author       : ~IOI~
 * @Date         : 2020-08-10 15:24:04
 * @LastEditors  : ~IOI~
 * @LastEditTime : 2020-08-10 15:52:05
 * @FilePath     : \number_conversion_services\src\Number\Number.php
 */

namespace Number;

class Number
{

    private  $dic_default = array(
        0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I',
        9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'R',
        18 => 'S', 19 => 'T', 20 => 'U', 21 => 'V', 22 => 'W', 23 => 'X', 24 => 'Y', 25 => 'Z'
    );
    private  $dic;

    public function __construct($dic = null)
    {
        if (empty($dic)) {
            $this->dic = $this->dic_default;
        } else {

            $this->dic = $dic;
        }
    }

    //十进制转换纯英文26进制
    public  function enid_24($int, $format = 8)
    {
        $dic = $this->dic;
        $arr = array();
        $loop = true;
        while ($loop) {
            $arr[] = $dic[intval(bcmod($int, 26))];
            $int = floor(bcdiv($int, 26));
            if ($int == 0) {
                $loop = false;
            }
        }
        $arr = array_pad($arr, $format, $dic[0]);
        return implode('', array_reverse($arr));
    }

    //纯英文26进制转换十进制
    public  function deid_24($id)
    {
        $dic = $this->dic;
        // 键值交换
        $dedic = array_flip($dic);
        // 去零
        $id = ltrim($id, $dic[0]);
        // 反转
        $id = strrev($id);
        $v = 0;
        for ($i = 0, $j = strlen($id); $i < $j; $i++) {
            $v = bcadd(bcmul($dedic[$id{
                $i}], bcpow(26, $i)), $v);
        }
        return $v;
    }

    /**
     * id转分享码 三个英文三个数字  英.数.英.数.英.数
     *
     * @param [type] $int
     * @return void
     */
    function id_to_six_code($int)
    {
        $jinzhi_10 =   $int % 1000;
        $jinzhi_24 =   intval($int / 1000);
        if ($jinzhi_10 < 10) {
            $jinzhi_10 = '0' . '0' . $jinzhi_10;
        } else if ($jinzhi_10 < 100) {
            $jinzhi_10 = '0' . $jinzhi_10;
        }
        $jinzhi_24 = $this->enid_24($jinzhi_24, 3);
        return substr($jinzhi_24, 0, 1) . substr($jinzhi_10, 0, 1) . substr($jinzhi_24, 1, 1) . substr($jinzhi_10, 1, 1) . substr($jinzhi_24, 2, 1) . substr($jinzhi_10, 2, 1);
    }

    /**
     * 分享码转id 三个英文三个数字  英.数.英.数.英.数 =》纯数字id
     *
     * @param [type] $int
     * @return void
     */
    function six_code_to_id($six_code)
    {
        $jinzhi_10 = substr($six_code, 1, 1) . substr($six_code, 3, 1) . substr($six_code, 5, 1);
        $jinzhi_24 = substr($six_code, 0, 1) . substr($six_code, 2, 1) . substr($six_code, 4, 1);
        $jinzhi_24 = $this->deid_24($jinzhi_24);
        $jinzhi_10 = intval($jinzhi_10);
        return intval($jinzhi_24 * 1000 + $jinzhi_10);
    }
}
