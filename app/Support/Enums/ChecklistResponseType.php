<?php

namespace App\Support\Enums;

enum ChecklistResponseType: string
{
    case Checkbox = 'checkbox';
    case Text = 'text';
    case Number = 'number';
    case Decimal = 'decimal';
    case SingleSelect = 'single_select';
    case MultiSelect = 'multi_select';
    case Photo = 'photo';
    case Signature = 'signature';
    case DateTime = 'date_time';
    case Temperature = 'temperature';
    case Pressure = 'pressure';
    case Instrument = 'instrument';
}
