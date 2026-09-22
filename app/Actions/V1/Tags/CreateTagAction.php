<?php

namespace App\Actions\V1\Tags;

use App\Models\Tag;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTagAction
{
    public function execute(array $input): Tag
    {
        $tenantId = app(TenantContext::class)->id();

        return DB::transaction(function () use ($input, $tenantId): Tag {
            if (empty($input['slug']) && ! empty($input['name'])) {
                $input['slug'] = Str::slug($input['name']);
            }

            $tag = Tag::create(array_merge($input, [
                'tenant_id' => $tenantId,
            ]));

            return $tag->loadCount('customers');
        });
    }
}
