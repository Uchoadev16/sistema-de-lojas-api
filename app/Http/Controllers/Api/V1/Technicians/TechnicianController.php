<?php

namespace App\Http\Controllers\Api\V1\Technicians;

use App\Actions\V1\Technicians\CreateTechnicianAction;
use App\Actions\V1\Technicians\DeleteTechnicianAction;
use App\Actions\V1\Technicians\RestoreTechnicianAction;
use App\Actions\V1\Technicians\UpdateTechnicianAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Technicians\StoreTechnicianRequest;
use App\Http\Requests\V1\Technicians\UpdateTechnicianRequest;
use App\Http\Resources\V1\TechnicianCollection;
use App\Http\Resources\V1\TechnicianResource;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Technician::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $availability = $request->input('availability_status');
        $serviceRegion = $request->input('service_region');
        $includeTrashed = (bool) $request->input('include_trashed', false);

        $query = Technician::query()
            ->withCount(['serviceTeams'])
            ->with(['user'])
            ->when($includeTrashed, fn ($q) => $q->withTrashed());

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('document', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('phone', 'ILIKE', "%{$search}%")
                    ->orWhere('mobile', 'ILIKE', "%{$search}%")
                    ->orWhere('whatsapp', 'ILIKE', "%{$search}%")
                    ->orWhere('specialty', 'ILIKE', "%{$search}%")
                    ->orWhere('professional_registration', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($availability) {
            $query->where('availability_status', $availability);
        }

        if ($serviceRegion) {
            $query->where('service_region', 'ILIKE', "%{$serviceRegion}%");
        }

        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDir);

        $technicians = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new TechnicianCollection($technicians))->response()->getData(true)
                : ['data' => TechnicianResource::collection($technicians)]
        );
    }

    public function store(StoreTechnicianRequest $request, CreateTechnicianAction $action)
    {
        $technician = $action->execute($request->validated(), $request->user());

        return $this->success(['technician' => new TechnicianResource($technician)], 201);
    }

    public function show(Technician $technician)
    {
        $this->authorize('view', $technician);

        $technician->loadCount(['serviceTeams'])
            ->load(['user', 'createdBy', 'serviceTeams']);

        return $this->success(['technician' => new TechnicianResource($technician)]);
    }

    public function update(UpdateTechnicianRequest $request, Technician $technician, UpdateTechnicianAction $action)
    {
        $result = $action->execute($technician, $request->validated());

        return $this->success(['technician' => new TechnicianResource($result)]);
    }

    public function destroy(Technician $technician, DeleteTechnicianAction $action)
    {
        $this->authorize('delete', $technician);

        $action->execute($technician);

        return $this->success([], 204);
    }

    public function restore(Request $request, string $technicianId, RestoreTechnicianAction $action)
    {
        $technician = Technician::withTrashed()->findOrFail($technicianId);
        $this->authorize('restore', $technician);

        $result = $action->execute($technician);

        return $this->success(['technician' => new TechnicianResource($result)]);
    }
}
