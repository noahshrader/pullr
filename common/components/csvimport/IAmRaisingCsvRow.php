<?php

namespace common\components\csvimport;


class IAmRaisingCsvRow implements ICSVRow
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
        return floatval($this->row[3]);
    }

    public function getName()
    {
        return trim($this->row[0]);
    }

    public function getComment()
    {
        return trim($this->row[1]);
    }

    public function getTimestamp()
    {
        return (new \DateTime($this->row[4]))->getTimestamp();
    }
} 