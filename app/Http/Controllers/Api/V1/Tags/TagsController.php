<?php

namespace App\Http\Controllers\Api\V1\Tags;

use App\Actions\V1\Tags\CreateTagAction;
use App\Actions\V1\Tags\DeleteTagAction;
use App\Actions\V1\Tags\UpdateTagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Tags\StoreTagRequest;
use App\Http\Requests\V1\Tags\UpdateTagRequest;
use App\Http\Resources\V1\TagCollection;
use App\Http\Resources\V1\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tag::class);

        $perPage = min(100, (int) $request->input('per_page', 0));
        $search = trim((string) $request->input('search', ''));
        $color = $request->input('color');
        $isSystem = $request->input('is_system');

        $query = Tag::query()->withCount('customers');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('slug', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        if ($color) {
            $query->where('color', $color);
        }

        if ($isSystem !== null) {
            $query->where('is_system', (bool) $isSystem);
        }

        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDir);

        $tags = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new TagCollection($tags))->response()->getData(true)
                : ['data' => TagResource::collection($tags)]
        );
    }

    public function store(StoreTagRequest $request, CreateTagAction $action)
    {
        $tag = $action->execute($request->validated());

        return $this->success(['tag' => new TagResource($tag)], 201);
    }

    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        $tag->loadCount('customers')->load('customers');

        return $this->success(['tag' => new TagResource($tag)]);
    }

    public function update(UpdateTagRequest $request, Tag $tag, UpdateTagAction $action)
    {
        $updated = $action->execute($tag, $request->validated());

        return $this->success(['tag' => new TagResource($updated)]);
    }

    public function destroy(Tag $tag, DeleteTagAction $action)
    {
        $this->authorize('delete', $tag);

        $action->execute($tag);

        return $this->success([], 204);
    }
}
