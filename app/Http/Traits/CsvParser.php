<?php

namespace App\Http\Traits;

trait CsvParser
{
    public function CsvToArray($content)
    {
        $rows = array_map('str_getcsv', explode(PHP_EOL, $content));
        $rowKeys = array_shift($rows);
        $formattedData = [];

        foreach ($rows as $row) {
            if (count($row) == count($rowKeys)) {
                $associatedRowData = array_combine($rowKeys, $row);

                if (empty($keyField)) {
                    $formattedData[] = $associatedRowData;
                } else {
                    $formattedData[$associatedRowData[$keyField]] = $associatedRowData;
                }
            }
        }

        return $formattedData;
    }
}
