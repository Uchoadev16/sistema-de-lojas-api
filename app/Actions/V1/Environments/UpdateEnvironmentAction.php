<?php

namespace App\Actions\V1\Environments;

use App\Models\Environment;
use Illuminate\Support\Facades\DB;

class UpdateEnvironmentAction
{
    public function execute(Environment $environment, array $input): Environment
    {
        return DB::transaction(function () use ($environment, $input): Environment {
            $environment->update($input);

            return $environment->load(['unit']);
        });
    }
}
