<?php

/*
|--------------------------------------------------------------------------
| Business import of the WireGuard networks (module CentralUi).
|
| Run it: php artisan deploy-env:terraform-modules --module_name=local:business-imports
| See module DeployEnv README.md
|--------------------------------------------------------------------------
*/

return [
    'deployments' => [
        '0003' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'proxtop_wireguard_networks.php',
                ],
            ],
        ],
    ],
];
