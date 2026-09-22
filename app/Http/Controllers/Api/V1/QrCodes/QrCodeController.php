<?php

namespace App\Http\Controllers\Api\V1\QrCodes;

use App\Actions\V1\QrCodes\CreateQrCodeAction;
use App\Actions\V1\QrCodes\DeleteQrCodeAction;
use App\Actions\V1\QrCodes\GenerateQrCodeAction;
use App\Actions\V1\QrCodes\LinkQrCodeAction;
use App\Actions\V1\QrCodes\RestoreQrCodeAction;
use App\Actions\V1\QrCodes\UnlinkQrCodeAction;
use App\Actions\V1\QrCodes\UpdateQrCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\QrCodes\GenerateQrCodeRequest;
use App\Http\Requests\V1\QrCodes\LinkQrCodeRequest;
use App\Http\Requests\V1\QrCodes\StoreQrCodeRequest;
use App\Http\Requests\V1\QrCodes\UpdateQrCodeRequest;
use App\Http\Resources\V1\QrCodeCollection;
use App\Http\Resources\V1\QrCodeResource;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', QrCode::class);

        $perPage = min(100, (int) $request->input('per_page', 25));
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $entityType = $request->input('entity_type');
        $isActive = $request->input('is_active');
        $includeTrashed = (bool) $request->input('include_trashed', false);

        $query = QrCode::query()
            ->with(['generatedBy'])
            ->when($includeTrashed, fn ($q) => $q->withTrashed());

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('code', 'ILIKE', "%{$search}%")
                    ->orWhere('entity_id', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($entityType) {
            $query->where('entity_type', $entityType);
        }

        if ($isActive !== null) {
            $query->where('is_active', (bool) $isActive);
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $qrCodes = $perPage > 0 ? $query->paginate($perPage) : $query->get();

        return $this->success(
            $perPage > 0
                ? (new QrCodeCollection($qrCodes))->response()->getData(true)
                : ['data' => QrCodeResource::collection($qrCodes)]
        );
    }

    public function store(StoreQrCodeRequest $request, CreateQrCodeAction $action)
    {
        $qrCode = $action->execute($request->validated(), $request->user());

        return $this->success(['qr_code' => new QrCodeResource($qrCode)], 201);
    }

    public function show(QrCode $qr_code)
    {
        $this->authorize('view', $qr_code);

        $qr_code->load(['generatedBy', 'entityEquipment']);

        return $this->success(['qr_code' => new QrCodeResource($qr_code)]);
    }

    public function update(UpdateQrCodeRequest $request, QrCode $qr_code, UpdateQrCodeAction $action)
    {
        $result = $action->execute($qr_code, $request->validated());

        return $this->success(['qr_code' => new QrCodeResource($result)]);
    }

    public function destroy(QrCode $qr_code, DeleteQrCodeAction $action)
    {
        $this->authorize('delete', $qr_code);

        $action->execute($qr_code);

        return $this->success([], 204);
    }

    public function restore(Request $request, string $qrCodeId, RestoreQrCodeAction $action)
    {
        $qrCode = QrCode::withTrashed()->findOrFail($qrCodeId);
        $this->authorize('restore', $qrCode);

        $result = $action->execute($qrCode);

        return $this->success(['qr_code' => new QrCodeResource($result)]);
    }

    public function generate(GenerateQrCodeRequest $request, GenerateQrCodeAction $action)
    {
        $this->authorize('generate', QrCode::class);

        $result = $action->execute($request->validated(), $request->user());

        if ($result instanceof Collection) {
            return $this->success([
                'qr_codes' => QrCodeResource::collection($result),
                'count' => $result->count(),
            ], 201);
        }

        return $this->success(['qr_code' => new QrCodeResource($result)], 201);
    }

    public function link(LinkQrCodeRequest $request, LinkQrCodeAction $action)
    {
        $this->authorize('link', QrCode::class);

        $validated = $request->validated();

        if (empty($validated['code'])) {
            throw ValidationException::withMessages([
                'code' => __('qr_codes.qr_id_or_code_required'),
            ]);
        }

        $result = $action->execute($validated, $request->user());

        return $this->success(['qr_code' => new QrCodeResource($result)]);
    }

    public function unlink(QrCode $qr_code, UnlinkQrCodeAction $action)
    {
        $this->authorize('unlink', $qr_code);

        $result = $action->execute($qr_code);

        return $this->success(['qr_code' => new QrCodeResource($result)]);
    }
}
