<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config of deployments. Can be updated by adding new identifiers.
    | See module DeployEnv README.md
    |--------------------------------------------------------------------------
    */

    'deployments' => [
        // Identifier to remember this deployment was already done.
        '0002'  => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'proxtop_servers.php',
                    'proxtop_property_templates.php',
                    'users.php',
                ],
            ],
        ],
    ],
];
