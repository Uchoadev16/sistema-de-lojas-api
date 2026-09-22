<?php

return [
    'admin_email' => env('SEEDER_ADMIN_EMAIL', 'admin@climaops.com.br'),
    'admin_password' => env('SEEDER_ADMIN_PASSWORD', 'password'),
    'force_in_production' => filter_var(env('SEEDER_FORCE_IN_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),
];
