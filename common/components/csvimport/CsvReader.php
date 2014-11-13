<?php

namespace common\components\csvimport;

use yii\base\Exception;

class CsvReader
{
    public function GetRows($file)
    {
        $plainData = $csv = array_map('str_getcsv', file($file));
        $rowClass = $this->getCsvProvider(array_shift($plainData));

        foreach($plainData as $plainRow)
        {
            $result[] = new $rowClass($plainRow);
        }

        return $result;
    }

    private function getCsvProvider($csvHeader)
    {
        if ((count($csvHeader) === 9) && ($csvHeader['5'] == 'Processor')) return 'common\components\csvimport\IAmRaisingCsvRow';
        if ((count($csvHeader) === 4) && ($csvHeader['3'] == 'Message')) return 'common\components\csvimport\TwitchAlertCsvRow';
        throw new Exception('Unknown CSV file format');
    }
} 