<?php

namespace App\Http\Controllers\Api\V1\Equipments;

use App\Actions\V1\Equipments\CreateEquipmentAction;
use App\Actions\V1\Equipments\DeleteEquipmentAction;
use App\Actions\V1\Equipments\RestoreEquipmentAction;
use App\Actions\V1\Equipments\UpdateEquipmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Equipments\StoreEquipmentRequest;
use App\Http\Requests\V1\Equipments\UpdateEquipmentRequest;
use App\Http\Resources\V1\EquipmentCollection;
use App\Http\Resources\V1\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Equipment::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $condition = $request->input('condition');
        $customerId = $request->input('customer_id');
        $categoryId = $request->input('category_id');
        $brandId = $request->input('brand_id');
        $modelId = $request->input('model_id');
        $includeTrashed = (bool) $request->input('include_trashed', false);

        $query = Equipment::query()
            ->with(['category', 'brand', 'model'])
            ->when($includeTrashed, fn ($q) => $q->withTrashed());

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('identifier', 'ILIKE', "%{$search}%")
                    ->orWhere('asset_tag', 'ILIKE', "%{$search}%")
                    ->orWhere('serial_number', 'ILIKE', "%{$search}%")
                    ->orWhere('qr_code_value', 'ILIKE', "%{$search}%")
                    ->orWhere('model_name', 'ILIKE', "%{$search}%")
                    ->orWhere('manufacturer', 'ILIKE', "%{$search}%")
                    ->orWhere('external_id', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($condition) {
            $query->where('condition', $condition);
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $equipments = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new EquipmentCollection($equipments))->response()->getData(true)
                : ['data' => EquipmentResource::collection($equipments)]
        );
    }

    public function store(StoreEquipmentRequest $request, CreateEquipmentAction $action)
    {
        $equipment = $action->execute($request->validated(), $request->user());

        return $this->success(['equipment' => new EquipmentResource($equipment)], 201);
    }

    public function show(Equipment $equipment)
    {
        $this->authorize('view', $equipment);

        $equipment->load(['category', 'brand', 'model', 'createdBy', 'qrCode']);

        return $this->success(['equipment' => new EquipmentResource($equipment)]);
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment, UpdateEquipmentAction $action)
    {
        $result = $action->execute($equipment, $request->validated());

        return $this->success(['equipment' => new EquipmentResource($result)]);
    }

    public function destroy(Equipment $equipment, DeleteEquipmentAction $action)
    {
        $this->authorize('delete', $equipment);

        $action->execute($equipment);

        return $this->success([], 204);
    }

    public function restore(Request $request, string $equipmentId, RestoreEquipmentAction $action)
    {
        $equipment = Equipment::withTrashed()->findOrFail($equipmentId);
        $this->authorize('restore', $equipment);

        $result = $action->execute($equipment);

        return $this->success(['equipment' => new EquipmentResource($result)]);
    }
}
