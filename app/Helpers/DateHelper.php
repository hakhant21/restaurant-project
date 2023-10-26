<?php

namespace App\Helpers;

use Exception;

class DateHelper
{
    public static function getDateRange($data){
        $result = null;
    if ($data) {
        // Parse the ISO 8601 date format directly with Carbon
     

        $result = [
            'from' => \Carbon\Carbon::parse($data[0])->format('Y-m-d 00:00:00'),
            'to' => \Carbon\Carbon::parse($data[1])->format('Y-m-d 23:59:59'),
        ];
    }

    return $result;
    }
}