<?php

namespace App\Http\Controllers\Api\V1\EquipmentModels;

use App\Actions\V1\EquipmentModels\CreateEquipmentModelAction;
use App\Actions\V1\EquipmentModels\DeleteEquipmentModelAction;
use App\Actions\V1\EquipmentModels\UpdateEquipmentModelAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EquipmentModels\StoreEquipmentModelRequest;
use App\Http\Requests\V1\EquipmentModels\UpdateEquipmentModelRequest;
use App\Http\Resources\V1\EquipmentModelCollection;
use App\Http\Resources\V1\EquipmentModelResource;
use App\Models\EquipmentModel;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', EquipmentModel::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $brandId = $request->input('brand_id');
        $categoryId = $request->input('category_id');

        $tenantId = app(TenantContext::class)->id();
        $query = EquipmentModel::query()
            ->where(function ($q) use ($tenantId): void {
                $q->whereNull('tenant_id')
                    ->orWhere('tenant_id', $tenantId);
            })
            ->withCount(['equipments'])
            ->with(['brand', 'category']);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('slug', 'ILIKE', "%{$search}%");
            });
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDir);

        $models = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new EquipmentModelCollection($models))->response()->getData(true)
                : ['data' => EquipmentModelResource::collection($models)]
        );
    }

    public function store(StoreEquipmentModelRequest $request, CreateEquipmentModelAction $action)
    {
        $model = $action->execute($request->validated());

        return $this->success(['model' => new EquipmentModelResource($model)], 201);
    }

    public function show(EquipmentModel $equipment_model)
    {
        $this->authorize('view', $equipment_model);

        $equipment_model->loadCount(['equipments'])
            ->load(['brand', 'category']);

        return $this->success(['model' => new EquipmentModelResource($equipment_model)]);
    }

    public function update(UpdateEquipmentModelRequest $request, EquipmentModel $equipment_model, UpdateEquipmentModelAction $action)
    {
        $model = $action->execute($equipment_model, $request->validated());

        return $this->success(['model' => new EquipmentModelResource($model)]);
    }

    public function destroy(EquipmentModel $equipment_model, DeleteEquipmentModelAction $action)
    {
        $this->authorize('delete', $equipment_model);

        $action->execute($equipment_model);

        return $this->success([], 204);
    }
}
