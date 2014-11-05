<?php

namespace common\components;

class PullrUtils extends \yii\base\Component
{
    /**
     * 
     * @param float $number
     * @param int $decimal
     */
    public static function formatNumber($number, $decimal=2)
    {
        $number = number_format($number, $decimal);
        
        return ( substr($number, -3)=== '.00') ? substr($number, 0, -3) : $number;
    }
}

