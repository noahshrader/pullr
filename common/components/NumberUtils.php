<?php

namespace common\components;

class NumberUtils extends \yii\base\Component
{
    /**
     * 
     * @param float $number
     * @param int $decimal
     */
    public static function formatNumber($number, $decimal)
    {
        $number = number_format($number, $decimal);
        
        return ( substr($number, -3)=== '.00') ? substr($number, 0, -3) : $number;
    }
}

