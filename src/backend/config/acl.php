<?php

return [
    'default_role' => 'User',
    'roles' => [
        'System Admin' => [
            'Manage Users',
            'View Users',
            'Manage Roles',
            'View Roles',
            'Manage Permissions',
            'View Permissions',
            'Manage Profile',
        ],
        'User' => [
            'Manage Profile',
        ],
    ],
];
