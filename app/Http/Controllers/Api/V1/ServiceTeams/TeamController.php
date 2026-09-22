<?php

namespace App\Http\Controllers\Api\V1\ServiceTeams;

use App\Actions\V1\ServiceTeams\AttachTeamMemberAction;
use App\Actions\V1\ServiceTeams\CreateServiceTeamAction;
use App\Actions\V1\ServiceTeams\DeleteServiceTeamAction;
use App\Actions\V1\ServiceTeams\DetachTeamMemberAction;
use App\Actions\V1\ServiceTeams\RestoreServiceTeamAction;
use App\Actions\V1\ServiceTeams\UpdateServiceTeamAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ServiceTeams\AttachMemberRequest;
use App\Http\Requests\V1\ServiceTeams\DetachMemberRequest;
use App\Http\Requests\V1\ServiceTeams\StoreServiceTeamRequest;
use App\Http\Requests\V1\ServiceTeams\UpdateServiceTeamRequest;
use App\Http\Resources\V1\ServiceTeamCollection;
use App\Http\Resources\V1\ServiceTeamResource;
use App\Models\ServiceTeam;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', ServiceTeam::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $leaderId = $request->input('leader_technician_id');
        $isActive = $request->input('is_active');
        $includeTrashed = (bool) $request->input('include_trashed', false);

        $query = ServiceTeam::query()
            ->withCount(['technicians'])
            ->with(['leader'])
            ->when($includeTrashed, fn ($q) => $q->withTrashed());

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

        if ($leaderId) {
            $query->where('leader_technician_id', $leaderId);
        }

        if ($isActive !== null) {
            $query->where('is_active', (bool) $isActive);
        }

        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDir);

        $teams = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new ServiceTeamCollection($teams))->response()->getData(true)
                : ['data' => ServiceTeamResource::collection($teams)]
        );
    }

    public function store(StoreServiceTeamRequest $request, CreateServiceTeamAction $action)
    {
        $team = $action->execute($request->validated(), $request->user());

        return $this->success(['service_team' => new ServiceTeamResource($team)], 201);
    }

    public function show(ServiceTeam $service_team)
    {
        $this->authorize('view', $service_team);

        $service_team->loadCount(['technicians'])
            ->load(['leader', 'createdBy', 'technicians']);

        return $this->success(['service_team' => new ServiceTeamResource($service_team)]);
    }

    public function update(UpdateServiceTeamRequest $request, ServiceTeam $service_team, UpdateServiceTeamAction $action)
    {
        $result = $action->execute($service_team, $request->validated());

        return $this->success(['service_team' => new ServiceTeamResource($result)]);
    }

    public function destroy(ServiceTeam $service_team, DeleteServiceTeamAction $action)
    {
        $this->authorize('delete', $service_team);

        $action->execute($service_team);

        return $this->success([], 204);
    }

    public function restore(Request $request, string $teamId, RestoreServiceTeamAction $action)
    {
        $team = ServiceTeam::withTrashed()->findOrFail($teamId);
        $this->authorize('restore', $team);

        $result = $action->execute($team);

        return $this->success(['service_team' => new ServiceTeamResource($result)]);
    }

    public function attachMember(AttachMemberRequest $request, ServiceTeam $service_team, AttachTeamMemberAction $action)
    {
        $this->authorize('attachMember', $service_team);

        $result = $action->execute($service_team, $request->validated());

        return $this->success(['service_team' => new ServiceTeamResource($result)]);
    }

    public function detachMember(DetachMemberRequest $request, ServiceTeam $service_team, DetachTeamMemberAction $action)
    {
        $this->authorize('detachMember', $service_team);

        $result = $action->execute($service_team, $request->validated());

        return $this->success(['service_team' => new ServiceTeamResource($result)]);
    }
}
