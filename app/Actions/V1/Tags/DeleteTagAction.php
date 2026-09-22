<?php

namespace App\Actions\V1\Tags;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class DeleteTagAction
{
    public function execute(Tag $tag): void
    {
        DB::transaction(function () use ($tag): void {
            if ($tag->is_system) {
                throw new \InvalidArgumentException('Não é possível excluir tags de sistema.');
            }
            $tag->customers()->detach();
            $tag->delete();
        });
    }
}
