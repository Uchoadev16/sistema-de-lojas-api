<?php

namespace App\Actions\V1\Environments;

use App\Models\Environment;
use Illuminate\Support\Facades\DB;

class DeleteEnvironmentAction
{
    public function execute(Environment $environment): void
    {
        DB::transaction(function () use ($environment): void {
            $environment->delete();
        });
    }
}
