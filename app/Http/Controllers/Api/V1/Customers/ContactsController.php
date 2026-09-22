<?php

namespace App\Http\Controllers\Api\V1\Customers;

use App\Actions\V1\CustomerContacts\CreateContactAction;
use App\Actions\V1\CustomerContacts\DeleteContactAction;
use App\Actions\V1\CustomerContacts\UpdateContactAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CustomerContacts\StoreContactRequest;
use App\Http\Requests\V1\CustomerContacts\UpdateContactRequest;
use App\Http\Resources\V1\CustomerContactCollection;
use App\Http\Resources\V1\CustomerContactResource;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function index(Request $request, Customer $customer)
    {
        $this->authorize('viewAny', CustomerContact::class);

        $perPage = min(100, (int) $request->input('per_page', 0));

        $query = $customer->contacts()->with('customer');

        $sortBy = $request->input('sort_by', 'is_primary');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir)->orderBy('name', 'asc');

        $contacts = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new CustomerContactCollection($contacts))->response()->getData(true)
                : ['data' => CustomerContactResource::collection($contacts)]
        );
    }

    public function store(StoreContactRequest $request, Customer $customer, CreateContactAction $action)
    {
        $contact = $action->execute($customer, $request->validated());

        return $this->success(['contact' => new CustomerContactResource($contact)], 201);
    }

    public function show(Customer $customer, CustomerContact $contact)
    {
        $this->authorize('view', $contact);

        abort_if($contact->customer_id !== $customer->id, 404);

        $contact->load('customer');

        return $this->success(['contact' => new CustomerContactResource($contact)]);
    }

    public function update(UpdateContactRequest $request, Customer $customer, CustomerContact $contact, UpdateContactAction $action)
    {
        abort_if($contact->customer_id !== $customer->id, 404);

        $updated = $action->execute($contact, $request->validated());

        return $this->success(['contact' => new CustomerContactResource($updated)]);
    }

    public function destroy(Customer $customer, CustomerContact $contact, DeleteContactAction $action)
    {
        abort_if($contact->customer_id !== $customer->id, 404);

        $this->authorize('delete', $contact);

        $action->execute($contact);

        return $this->success([], 204);
    }
}
