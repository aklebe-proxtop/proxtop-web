<?php

/*
 * Groups and desktop items of users (example values).
 * can_create false: the users must already exist (e.g. imported by "ldap:sync-all"), they are only updated.
 */
return [
    'version'    => '2.0',
    // class of eloquent model
    'model'      => \Modules\CentralUi\app\Models\User::class,
    // create data if not exists (default true)
    'can_create' => false,
    // update data if exists and data differ (default false)
    'can_update' => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'    => ['name'],
    // relations to update/create
    'relations'  => [
        'observeItems' => [
            // relation method which have to exists
            'method'      => 'observeItems',
            // column(s) to find specific #sync_relations items below
            'columns'     => ['vm.extern_vm_id'],
            // delete items if not listed here (default: false)
            'auto_delete' => false,
        ],
        'aclGroups'    => [
            // relation method which have to exists
            'method'      => 'aclGroups',
            // column(s) to find specific #sync_relations items below
            'columns'     => 'name',
            // delete items if not listed here (default: false)
            'auto_delete' => false,
        ],
    ],
    // data rows itself
    'data'       => [
        [
            'is_enabled'      => 1,
            // several users with the same settings
            'name'            => [
                'admin',
            ],
            '#sync_relations' => [
                'aclGroups'    => [
                    'Admins',
                    'Members',
                    'Server Proxmox Observers',
                ],
                'observeItems' => [
                    ['is_enabled' => 1, 'position' => 1000, 'vm.extern_vm_id' => '100'],
                    ['is_enabled' => 1, 'position' => 2000, 'vm.extern_vm_id' => '101'],
                    ['is_enabled' => 1, 'position' => 3000, 'vm.extern_vm_id' => '102'],
                ],
            ],
        ],
        [
            'is_enabled'      => 1,
            'name'            => [
                'alice',
                'bob',
            ],
            '#sync_relations' => [
                'aclGroups'    => [
                    'Members',
                    'Server Proxmox Observers',
                ],
                'observeItems' => [
                    ['is_enabled' => 1, 'position' => 1000, 'vm.extern_vm_id' => '101'],
                ],
            ],
        ],
    ],
];
