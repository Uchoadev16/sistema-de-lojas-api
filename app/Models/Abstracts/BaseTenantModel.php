<?php

namespace App\Models\Abstracts;

use App\Support\Traits\HasTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseTenantModel extends BaseGlobalModel
{
    use HasTenant;
    use SoftDeletes;
}
