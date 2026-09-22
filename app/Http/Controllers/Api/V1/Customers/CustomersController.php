<?php

namespace App\Http\Controllers\Api\V1\Customers;

use App\Actions\V1\Customers\CreateCustomerAction;
use App\Actions\V1\Customers\DeleteCustomerAction;
use App\Actions\V1\Customers\RestoreCustomerAction;
use App\Actions\V1\Customers\UpdateCustomerAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Customers\AttachCustomerTagsRequest;
use App\Http\Requests\V1\Customers\DetachCustomerTagsRequest;
use App\Http\Requests\V1\Customers\StoreCustomerRequest;
use App\Http\Requests\V1\Customers\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use App\Models\Tag;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $type = $request->input('customer_type');
        $isActive = $request->input('is_active');
        $tagIds = $request->input('tag_ids', []);

        $query = Customer::query()
            ->withCount(['contacts', 'units', 'tags'])
            ->with(['tags']);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('legal_name', 'ILIKE', "%{$search}%")
                    ->orWhere('trade_name', 'ILIKE', "%{$search}%")
                    ->orWhere('tax_id', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('phone', 'ILIKE', "%{$search}%")
                    ->orWhere('mobile', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('customer_type', $type);
        }

        if ($isActive !== null) {
            $query->where('is_active', (bool) $isActive);
        }

        if (! empty($tagIds) && is_array($tagIds)) {
            $query->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds));
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

        $customers = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new CustomerCollection($customers))->response()->getData(true)
                : ['data' => CustomerResource::collection($customers)]
        );
    }

    public function store(StoreCustomerRequest $request, CreateCustomerAction $action)
    {
        $customer = $action->execute($request->validated());

        return $this->success(['customer' => new CustomerResource($customer)], 201);
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $customer->load(['contacts', 'addresses', 'tags', 'units', 'createdBy'])
            ->loadCount(['contacts', 'units', 'tags']);

        return $this->success(['customer' => new CustomerResource($customer)]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer, UpdateCustomerAction $action)
    {
        $updated = $action->execute($customer, $request->validated());

        return $this->success(['customer' => new CustomerResource($updated)]);
    }

    public function destroy(Customer $customer, DeleteCustomerAction $action)
    {
        $this->authorize('delete', $customer);

        $action->execute($customer);

        return $this->success([], 204);
    }

    public function restore(string $customerId, RestoreCustomerAction $action)
    {
        $customer = Customer::withTrashed()->findOrFail($customerId);
        $this->authorize('restore', $customer);

        $restored = $action->execute($customer);

        return $this->success(['customer' => new CustomerResource($restored)]);
    }

    public function attachTag(AttachCustomerTagsRequest $request, Customer $customer)
    {
        $this->authorize('attachTag', $customer);

        $validated = $request->validated();

        $customer->tags()->syncWithoutDetaching($validated['tag_ids']);

        $customer->load('tags');

        return $this->success(['customer' => new CustomerResource($customer)]);
    }

    public function detachTag(DetachCustomerTagsRequest $request, Customer $customer, ?Tag $tag = null)
    {
        $this->authorize('detachTag', $customer);

        $validated = $request->validated();

        $tagIds = $validated['tag_ids'] ?? [];
        if ($tag) {
            $tagIds[] = $tag->id;
        }

        $customer->tags()->detach($tagIds);

        $customer->load('tags');

        return $this->success(['customer' => new CustomerResource($customer)]);
    }
}
