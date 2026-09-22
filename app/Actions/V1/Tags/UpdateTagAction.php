<?php

namespace App\Actions\V1\Tags;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateTagAction
{
    public function execute(Tag $tag, array $input): Tag
    {
        return DB::transaction(function () use ($tag, $input): Tag {
            if (isset($input['name']) && (empty($input['slug']) || $input['slug'] === null)) {
                $input['slug'] = Str::slug($input['name']);
            }

            $tag->update($input);

            return $tag->loadCount('customers');
        });
    }
}
