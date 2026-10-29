<?php

/*
 * Property templates: which VMs belong together, who may start/stop them and the schedules (example values).
 */
return [
    'version'    => '2.0',
    // class of eloquent model
    'model'      => \Modules\CentralUi\app\Models\ProxtopPropertyTemplate::class,
    // create data if not exists (default true)
    'can_create' => true,
    // update data if exists and data differ (default false)
    'can_update' => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'    => ['name'],
    // relations to update/create
    'relations'  => [
        'vms'       => [
            // relation method which have to exists
            'method'      => 'vms',
            // column(s) to find specific #sync_relations items below
            'columns'     => 'extern_vm_id',
            // delete items if not listed here (default: false)
            'auto_delete' => false,
        ],
        'schedules' => [
            // relation method which have to exists
            'method'      => 'schedules',
            // column(s) to find specific #sync_relations items below
            'columns'     => ['command_code', 'template_id'],
            // delete items if not listed here (default: false)
            'auto_delete' => false,
        ],
    ],
    // data rows itself
    'data'       => [
        [
            'name'             => 'Important VMs',
            'is_enabled'       => 1,
            'acl_res_shutdown' => ["admin"],
            'acl_res_start'    => ["admin"],
            'acl_res_edit'     => ["admin"],
            'cpu_idle'         => 15,
            '#sync_relations'  => [
                'vms'       => [
                    '100',
                    '102',
                ],
                'schedules' => [
                    [
                        'is_enabled'      => 1,
                        'command_code'    => 'SHOULD_ONLINE',
                        'cron_expression' => '* * * * *',
                        'duration'        => 0,
                    ],
                ],
            ],
        ],
        [
            'name'             => 'Media',
            'is_enabled'       => 1,
            'acl_res_shutdown' => ["member"],
            'acl_res_start'    => ["member"],
            'acl_res_edit'     => ["member"],
            'cpu_idle'         => 10,
            '#sync_relations'  => [
                'vms'       => [
                    '101',
                ],
                'schedules' => [
                    [
                        'is_enabled'      => 1,
                        'command_code'    => 'CAN_ONLINE',
                        'cron_expression' => '* * * * *',
                        'duration'        => 0,
                    ],
                    [
                        'is_enabled'      => 1,
                        'command_code'    => 'CAN_OFFLINE',
                        'cron_expression' => '* * * * *',
                        'duration'        => 0,
                    ],
                ],
            ],
        ],
    ],
];
