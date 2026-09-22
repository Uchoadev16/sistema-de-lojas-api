<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [\App\Http\Controllers\Api\V1\Auth\RegisterController::class, 'store'])
        ->middleware('throttle:register');
    Route::post('login', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'store'])
        ->middleware('throttle:login');
    Route::post('forgot-password', [\App\Http\Controllers\Api\V1\Auth\ForgotPasswordController::class, 'store'])
        ->middleware('throttle:forgot');
    Route::post('reset-password', [\App\Http\Controllers\Api\V1\Auth\ResetPasswordController::class, 'store'])
        ->middleware('throttle:reset')
        ->name('password.reset');
    Route::post('verify-email/{id}/{hash}', [\App\Http\Controllers\Api\V1\Auth\VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:verify'])
        ->name('verification.verify');

    Route::middleware(['auth:sanctum', 'resolve_tenant'])->group(function (): void {
        Route::post('logout', [\App\Http\Controllers\Api\V1\Auth\LogoutController::class, 'destroy']);
        Route::post('logout-all', [\App\Http\Controllers\Api\V1\Auth\LogoutAllController::class, 'destroy']);
        Route::get('me', [\App\Http\Controllers\Api\V1\Auth\MeController::class, 'show']);
        Route::post('refresh-token', [\App\Http\Controllers\Api\V1\Auth\RefreshTokenController::class, 'store']);
        Route::post('change-password', [\App\Http\Controllers\Api\V1\Auth\ChangePasswordController::class, 'store']);

        Route::prefix('mfa')->group(function (): void {
            Route::get('methods', [\App\Http\Controllers\Api\V1\Auth\MfaController::class, 'index']);
            Route::post('totp/setup', [\App\Http\Controllers\Api\V1\Auth\MfaController::class, 'setupTotp']);
            Route::post('totp/confirm', [\App\Http\Controllers\Api\V1\Auth\MfaController::class, 'confirmTotp']);
            Route::post('verify', [\App\Http\Controllers\Api\V1\Auth\MfaController::class, 'verify']);
            Route::post('disable', [\App\Http\Controllers\Api\V1\Auth\MfaController::class, 'disable']);
        });
    });
});

Route::middleware(['auth:sanctum', 'resolve_tenant'])->group(function (): void {
    Route::apiSingleton('tenant', \App\Http\Controllers\Api\V1\Tenants\TenantController::class)
        ->only(['show', 'update']);
    Route::post('tenants/switch', [\App\Http\Controllers\Api\V1\Tenants\TenantSwitchController::class, 'store']);

    Route::apiResource('users', \App\Http\Controllers\Api\V1\Users\UserController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::prefix('users/{user}')->group(function (): void {
        Route::post('invite', [\App\Http\Controllers\Api\V1\Users\UserInviteController::class, 'store']);
        Route::post('resend-invite', [\App\Http\Controllers\Api\V1\Users\UserInviteController::class, 'resend']);
        Route::post('reset-password', [\App\Http\Controllers\Api\V1\Users\UserPasswordController::class, 'reset']);
        Route::patch('deactivate', [\App\Http\Controllers\Api\V1\Users\UserStatusController::class, 'deactivate']);
        Route::patch('activate', [\App\Http\Controllers\Api\V1\Users\UserStatusController::class, 'activate']);
    });

    Route::apiResource('roles', \App\Http\Controllers\Api\V1\Roles\RoleController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('permissions', [\App\Http\Controllers\Api\V1\Permissions\PermissionController::class, 'index']);
    Route::post('roles/{role}/permissions', [\App\Http\Controllers\Api\V1\Roles\RolePermissionController::class, 'sync']);
});
