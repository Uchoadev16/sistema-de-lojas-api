<?php

namespace App\Http\Controllers\Api\V1\Units;

use App\Actions\V1\Units\CreateUnitAction;
use App\Actions\V1\Units\DeleteUnitAction;
use App\Actions\V1\Units\RestoreUnitAction;
use App\Actions\V1\Units\UpdateUnitAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Units\StoreUnitRequest;
use App\Http\Requests\V1\Units\UpdateUnitRequest;
use App\Http\Resources\V1\UnitCollection;
use App\Http\Resources\V1\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Unit::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $customerId = $request->input('customer_id');
        $status = $request->input('status');
        $isActive = $request->input('is_active');

        $query = Unit::query()
            ->withCount('environments')
            ->with(['customer', 'address', 'responsibleContact']);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('code', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($isActive !== null) {
            $query->where('is_active', (bool) $isActive);
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

        $units = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new UnitCollection($units))->response()->getData(true)
                : ['data' => UnitResource::collection($units)]
        );
    }

    public function store(StoreUnitRequest $request, CreateUnitAction $action)
    {
        $unit = $action->execute($request->validated());

        return $this->success(['unit' => new UnitResource($unit)], 201);
    }

    public function show(Unit $unit)
    {
        $this->authorize('view', $unit);

        $unit->load(['customer', 'address', 'responsibleContact', 'environments' => fn ($q) => $q->withCount('equipments')])
            ->loadCount('environments');

        return $this->success(['unit' => new UnitResource($unit)]);
    }

    public function update(UpdateUnitRequest $request, Unit $unit, UpdateUnitAction $action)
    {
        $updated = $action->execute($unit, $request->validated());

        return $this->success(['unit' => new UnitResource($updated)]);
    }

    public function destroy(Unit $unit, DeleteUnitAction $action)
    {
        $this->authorize('delete', $unit);

        $action->execute($unit);

        return $this->success([], 204);
    }

    public function restore(string $unitId, RestoreUnitAction $action)
    {
        $unit = Unit::withTrashed()->findOrFail($unitId);
        $this->authorize('restore', $unit);

        $result = $action->execute($unit);

        return $this->success(['unit' => new UnitResource($result)]);
    }
}
