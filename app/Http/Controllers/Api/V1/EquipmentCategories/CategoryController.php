<?php

namespace App\Http\Controllers\Api\V1\EquipmentCategories;

use App\Actions\V1\EquipmentCategories\CreateEquipmentCategoryAction;
use App\Actions\V1\EquipmentCategories\DeleteEquipmentCategoryAction;
use App\Actions\V1\EquipmentCategories\UpdateEquipmentCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EquipmentCategories\StoreEquipmentCategoryRequest;
use App\Http\Requests\V1\EquipmentCategories\UpdateEquipmentCategoryRequest;
use App\Http\Resources\V1\EquipmentCategoryCollection;
use App\Http\Resources\V1\EquipmentCategoryResource;
use App\Models\EquipmentCategory;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', EquipmentCategory::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $isSystem = $request->input('is_system');

        $tenantId = app(TenantContext::class)->id();
        $query = EquipmentCategory::query()
            ->where(function ($q) use ($tenantId): void {
                $q->whereNull('tenant_id')
                    ->orWhere('tenant_id', $tenantId);
            })
            ->withCount(['equipment_models', 'equipments']);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('code', 'ILIKE', "%{$search}%")
                    ->orWhere('slug', 'ILIKE', "%{$search}%");
            });
        }

        if ($isSystem !== null) {
            $query->where('is_system', (bool) $isSystem);
        }

        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDir);

        $categories = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new EquipmentCategoryCollection($categories))->response()->getData(true)
                : ['data' => EquipmentCategoryResource::collection($categories)]
        );
    }

    public function store(StoreEquipmentCategoryRequest $request, CreateEquipmentCategoryAction $action)
    {
        $category = $action->execute($request->validated());

        return $this->success(['category' => new EquipmentCategoryResource($category)], 201);
    }

    public function show(EquipmentCategory $equipment_category)
    {
        $this->authorize('view', $equipment_category);

        $equipment_category->loadCount(['equipment_models', 'equipments'])
            ->load([
                'equipment_models' => fn ($q) => $q->with('brand'),
            ]);

        return $this->success(['category' => new EquipmentCategoryResource($equipment_category)]);
    }

    public function update(UpdateEquipmentCategoryRequest $request, EquipmentCategory $equipment_category, UpdateEquipmentCategoryAction $action)
    {
        $category = $action->execute($equipment_category, $request->validated());

        return $this->success(['category' => new EquipmentCategoryResource($category)]);
    }

    public function destroy(EquipmentCategory $equipment_category, DeleteEquipmentCategoryAction $action)
    {
        $this->authorize('delete', $equipment_category);

        $action->execute($equipment_category);

        return $this->success([], 204);
    }
}
