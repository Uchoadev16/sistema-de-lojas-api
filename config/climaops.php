<?php

return [
    'allowed_reset_return_hosts' => array_values(array_filter(array_unique(array_merge(
        [parse_url(config('app.url'), PHP_URL_HOST)],
        array_map(
            static fn ($url) => parse_url(trim($url), PHP_URL_HOST),
            explode(',', env('FRONTEND_WHITELIST_URLS', '')),
        )
    )))),
    'tenant_default_timezone' => env('TENANT_DEFAULT_TIMEZONE', 'America/Sao_Paulo'),
    'max_bulk_qrcode_generation' => (int) env('MAX_BULK_QRCODE', 500),
];
