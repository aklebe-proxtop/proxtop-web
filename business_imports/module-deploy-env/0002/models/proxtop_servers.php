<?php

// The token comes from the env var PROXMOX_TOKEN_SECRET (set in project/config/secrets.yaml under "env",
// not versioned), read via config: env() returns nothing here once the config is cached.
$tokenSecret = config('central-ui.proxmox.token_secret') ?: throw new Exception('PROXMOX_TOKEN_SECRET is missing, see project/config/secrets.example.yaml');

/*
 * Proxmox servers and the VMs shown on the desktop (example values).
 * extern_vm_id: the VM id in Proxmox, used by the property templates and the users below.
 */
return [
    'version'    => '2.0',
    // class of eloquent model
    'model'      => \Modules\CentralUi\app\Models\ProxtopServer::class,
    // create data if not exists (default true)
    'can_create' => true,
    // update data if exists and data differ (default false)
    'can_update' => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'    => ['host'],
    // relations to update/create
    'relations'  => [
        'vms' => [
            // relation method which have to exists
            'method'      => 'vms',
            // column(s) to find specific #sync_relations items below
            'columns'     => ['server_id', 'extern_vm_id'],
            // delete items if not listed here (default: false)
            'auto_delete' => false,
        ],
    ],
    // data rows itself
    'data'       => [
        [
            'datacenter_id'   => 1,
            'api_allowed'     => 1,
            'host'            => 'https://pve.example.com',
            'user'            => 'root@pam',
            'token_name'      => 'proxtop',
            'token_secret'    => $tokenSecret,
            'description'     => 'Homeserver',
            '#sync_relations' => [
                'vms' => [
                    [
                        'extern_vm_id' => '100',
                        'host'         => 'nas.example.com',
                        'url'          => 'https://nas.example.com',
                    ],
                    [
                        'extern_vm_id' => '101',
                        'host'         => 'media.example.com',
                        'url'          => 'https://media.example.com',
                    ],
                    [
                        'extern_vm_id' => '102',
                        'host'         => 'dns.example.com',
                        'url'          => 'https://192.0.2.2',
                    ],
                ],
            ],
        ],
    ],
];
