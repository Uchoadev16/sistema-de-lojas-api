<?php

namespace App\Support\Enums;

enum SignatureType: string
{
    case Client = 'client';
    case Technician = 'technician';
    case Witness = 'witness';
    case Responsible = 'responsible';
    case Other = 'other';
}
