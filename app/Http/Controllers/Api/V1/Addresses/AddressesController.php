<?php

namespace App\Http\Controllers\Api\V1\Addresses;

use App\Actions\V1\Addresses\CreateAddressAction;
use App\Actions\V1\Addresses\DeleteAddressAction;
use App\Actions\V1\Addresses\UpdateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Addresses\StoreAddressRequest;
use App\Http\Requests\V1\Addresses\UpdateAddressRequest;
use App\Http\Resources\V1\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Address::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $addressType = $request->input('address_type');
        $city = $request->input('city');
        $state = $request->input('state');
        $postalCode = $request->input('postal_code');

        $query = Address::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('street', 'ILIKE', "%{$search}%")
                    ->orWhere('city', 'ILIKE', "%{$search}%")
                    ->orWhere('neighborhood', 'ILIKE', "%{$search}%")
                    ->orWhere('postal_code', 'ILIKE', "%{$search}%")
                    ->orWhere('complement', 'ILIKE', "%{$search}%");
            });
        }

        if ($addressType) {
            $query->where('address_type', $addressType);
        }

        if ($city) {
            $query->where('city', 'ILIKE', "%{$city}%");
        }

        if ($state) {
            $query->where('state', $state);
        }

        if ($postalCode) {
            $query->where('postal_code', 'ILIKE', "%{$postalCode}%");
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $addresses = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success([
            'data' => AddressResource::collection($addresses),
        ]);
    }

    public function store(StoreAddressRequest $request, CreateAddressAction $action)
    {
        $address = $action->execute($request->validated());

        return $this->success(['address' => new AddressResource($address)], 201);
    }

    public function show(Address $address)
    {
        $this->authorize('view', $address);

        return $this->success(['address' => new AddressResource($address)]);
    }

    public function update(UpdateAddressRequest $request, Address $address, UpdateAddressAction $action)
    {
        $updated = $action->execute($address, $request->validated());

        return $this->success(['address' => new AddressResource($updated)]);
    }

    public function destroy(Address $address, DeleteAddressAction $action)
    {
        $this->authorize('delete', $address);

        $action->execute($address);

        return $this->success([], 204);
    }
}
