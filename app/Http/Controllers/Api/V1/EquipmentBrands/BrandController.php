<?php

namespace App\Http\Controllers\Api\V1\EquipmentBrands;

use App\Actions\V1\EquipmentBrands\CreateEquipmentBrandAction;
use App\Actions\V1\EquipmentBrands\DeleteEquipmentBrandAction;
use App\Actions\V1\EquipmentBrands\UpdateEquipmentBrandAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EquipmentBrands\StoreEquipmentBrandRequest;
use App\Http\Requests\V1\EquipmentBrands\UpdateEquipmentBrandRequest;
use App\Http\Resources\V1\EquipmentBrandCollection;
use App\Http\Resources\V1\EquipmentBrandResource;
use App\Models\EquipmentBrand;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', EquipmentBrand::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $isSystem = $request->input('is_system');

        $tenantId = app(TenantContext::class)->id();
        $query = EquipmentBrand::query()
            ->where(function ($q) use ($tenantId): void {
                $q->whereNull('tenant_id')
                    ->orWhere('tenant_id', $tenantId);
            })
            ->withCount(['equipment_models', 'equipments']);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('slug', 'ILIKE', "%{$search}%")
                    ->orWhere('website', 'ILIKE', "%{$search}%");
            });
        }

        if ($isSystem !== null) {
            $query->where('is_system', (bool) $isSystem);
        }

        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDir);

        $brands = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new EquipmentBrandCollection($brands))->response()->getData(true)
                : ['data' => EquipmentBrandResource::collection($brands)]
        );
    }

    public function store(StoreEquipmentBrandRequest $request, CreateEquipmentBrandAction $action)
    {
        $brand = $action->execute($request->validated());

        return $this->success(['brand' => new EquipmentBrandResource($brand)], 201);
    }

    public function show(EquipmentBrand $equipment_brand)
    {
        $this->authorize('view', $equipment_brand);

        $equipment_brand->loadCount(['equipment_models', 'equipments'])
            ->load([
                'equipment_models' => fn ($q) => $q->with('category'),
            ]);

        return $this->success(['brand' => new EquipmentBrandResource($equipment_brand)]);
    }

    public function update(UpdateEquipmentBrandRequest $request, EquipmentBrand $equipment_brand, UpdateEquipmentBrandAction $action)
    {
        $brand = $action->execute($equipment_brand, $request->validated());

        return $this->success(['brand' => new EquipmentBrandResource($brand)]);
    }

    public function destroy(EquipmentBrand $equipment_brand, DeleteEquipmentBrandAction $action)
    {
        $this->authorize('delete', $equipment_brand);

        $action->execute($equipment_brand);

        return $this->success([], 204);
    }
}
