<?php

namespace App\Support\Enums;

enum EvidenceType: string
{
    case Photo = 'photo';
    case Video = 'video';
    case Document = 'document';
    case Measurement = 'measurement';
    case Note = 'note';
}
