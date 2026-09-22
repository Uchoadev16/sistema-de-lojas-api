<?php

namespace App\Support\Enums;

enum ReportFormat: string
{
    case Pdf = 'pdf';
    case Csv = 'csv';
    case Xlsx = 'xlsx';
    case Html = 'html';
}
