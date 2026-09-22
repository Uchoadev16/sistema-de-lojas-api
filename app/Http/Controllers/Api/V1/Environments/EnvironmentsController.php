<?php

namespace App\Http\Controllers\Api\V1\Environments;

use App\Actions\V1\Environments\CreateEnvironmentAction;
use App\Actions\V1\Environments\DeleteEnvironmentAction;
use App\Actions\V1\Environments\UpdateEnvironmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Environments\StoreEnvironmentRequest;
use App\Http\Requests\V1\Environments\UpdateEnvironmentRequest;
use App\Http\Resources\V1\EnvironmentCollection;
use App\Http\Resources\V1\EnvironmentResource;
use App\Models\Environment;
use Illuminate\Http\Request;

class EnvironmentsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Environment::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $unitId = $request->input('unit_id');
        $status = $request->input('status');
        $isActive = $request->input('is_active');
        $floor = $request->input('floor');

        $query = Environment::query()->with(['unit']);

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('code', 'ILIKE', "%{$search}%")
                    ->orWhere('purpose', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($isActive !== null) {
            $query->where('is_active', (bool) $isActive);
        }

        if ($floor) {
            $query->where('floor', $floor);
        }

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $environments = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new EnvironmentCollection($environments))->response()->getData(true)
                : ['data' => EnvironmentResource::collection($environments)]
        );
    }

    public function store(StoreEnvironmentRequest $request, CreateEnvironmentAction $action)
    {
        $environment = $action->execute($request->validated());

        return $this->success(['environment' => new EnvironmentResource($environment)], 201);
    }

    public function show(Environment $environment)
    {
        $this->authorize('view', $environment);

        $environment->load(['unit']);

        return $this->success(['environment' => new EnvironmentResource($environment)]);
    }

    public function update(UpdateEnvironmentRequest $request, Environment $environment, UpdateEnvironmentAction $action)
    {
        $updated = $action->execute($environment, $request->validated());

        return $this->success(['environment' => new EnvironmentResource($updated)]);
    }

    public function destroy(Environment $environment, DeleteEnvironmentAction $action)
    {
        $this->authorize('delete', $environment);

        $action->execute($environment);

        return $this->success([], 204);
    }
}
