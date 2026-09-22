<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Actions\V1\Users\CreateUserAction;
use App\Actions\V1\Users\DeleteUserAction;
use App\Actions\V1\Users\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\StoreUserRequest;
use App\Http\Requests\V1\Users\UpdateUserRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $roleId = $request->input('role_id');

        $tenantId = app(TenantContext::class)->id();
        $query = User::query()
            ->whereHas('userTenants', function ($q) use ($tenantId, $status, $roleId): void {
                $q->where('tenant_id', $tenantId);
                if ($status) {
                    $q->where('status', $status);
                }
                if ($roleId) {
                    $q->where('role_id', $roleId);
                }
            })
            ->with([
                'userTenants' => fn ($q) => $q->where('tenant_id', $tenantId)->with('role'),
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('document', 'ILIKE', "%{$search}%")
                    ->orWhere('phone', 'ILIKE', "%{$search}%")
                    ->orWhere('mobile', 'ILIKE', "%{$search}%");
            });
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $users = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new UserCollection($users))->response()->getData(true)
                : ['data' => UserResource::collection($users)]
        );
    }

    public function store(StoreUserRequest $request, CreateUserAction $createAction)
    {
        $result = $createAction->execute($request->validated(), $request->user());

        return $this->success([
            'user' => new UserResource($result['user']),
            'invite_sent' => $result['invite_sent'],
        ], 201);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        $tenantId = app(TenantContext::class)->id();
        $user->load([
            'userTenants' => fn ($q) => $q->where('tenant_id', $tenantId)->with(['role.permissions']),
            'mfaMethods',
        ]);

        return $this->success(['user' => new UserResource($user)]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $updateAction)
    {
        $result = $updateAction->execute($user, $request->validated());

        return $this->success(['user' => new UserResource($result['user'])]);
    }

    public function destroy(User $user, DeleteUserAction $action)
    {
        $this->authorize('delete', $user);

        $action->execute($user);

        return $this->success([], 204);
    }
}
