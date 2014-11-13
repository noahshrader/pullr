<?php

namespace common\components\csvimport;


class TwitchAlertCsvRow implements ICSVRow
{
    private $row;

    public function __construct($csvRow)
    {
        $this->row =  $csvRow;
    }

    public function getEmail()
    {
        return '';
    }

    public function getAmount()
    {
        return floatval(str_replace('$', '', $this->row[2]));
    }

    public function getName()
    {
        return trim($this->row[1]);
    }

    public function getComment()
    {
        return trim($this->row[3]);
    }

    public function getTimestamp()
    {
        return (new \DateTime($this->row[0]))->getTimestamp();
    }
} 