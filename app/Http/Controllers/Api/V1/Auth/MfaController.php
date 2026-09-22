<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\ConfirmMfaTotpAction;
use App\Actions\V1\Auth\DisableMfaAction;
use App\Actions\V1\Auth\SetupMfaTotpAction;
use App\Actions\V1\Auth\VerifyMfaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\MfaConfirmTotpRequest;
use App\Http\Requests\V1\Auth\MfaDisableRequest;
use App\Http\Requests\V1\Auth\MfaSetupTotpRequest;
use App\Http\Requests\V1\Auth\MfaVerifyRequest;
use App\Http\Resources\V1\MfaMethodResource;

class MfaController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $methods = $user->mfaMethods()->where('is_enabled', true)->orderBy('is_default', 'desc')->get();

        return $this->success([
            'mfa_enabled' => (bool) $user->mfa_enabled,
            'methods' => MfaMethodResource::collection($methods),
        ]);
    }

    public function setupTotp(MfaSetupTotpRequest $request, SetupMfaTotpAction $action)
    {
        $result = $action->execute($request->user(), $request->validated());

        return $this->success([
            'mfa_method_id' => $result['mfa_method']->id,
            'secret' => $result['secret'],
            'provisioning_uri' => $result['provisioning_uri'],
            'qr_code_data_uri' => $result['qr_code_data_uri'],
            'method' => new MfaMethodResource($result['mfa_method']),
        ], 201);
    }

    public function confirmTotp(MfaConfirmTotpRequest $request, ConfirmMfaTotpAction $action)
    {
        $result = $action->execute($request->user(), $request->validated());

        return $this->success([
            'enabled' => true,
            'method' => new MfaMethodResource($result['mfa_method']),
            'recovery_codes' => $result['recovery_codes'],
            'warning' => 'Store recovery codes securely. They are not retrievable again.',
        ]);
    }

    public function verify(MfaVerifyRequest $request, VerifyMfaAction $action)
    {
        $user = $request->user();
        $ok = $action->execute($user, $request->validated());

        return $this->success(['verified' => $ok], $ok ? 200 : 401);
    }

    public function disable(MfaDisableRequest $request, DisableMfaAction $action)
    {
        $result = $action->execute($request->user(), $request->validated());

        return $this->success($result);
    }
}
