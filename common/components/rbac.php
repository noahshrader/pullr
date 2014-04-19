<?php

use yii\rbac\Item;

return [
    'items' => [
        // HERE ARE YOUR MANAGEMENT TASKS manageThing0 & manageThing1 - examples
        'emailConfirmation' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to email confirmation page', 'ruleName' => NULL, 'data' => NULL],
        'frontend' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to frontend of application', 'ruleName' => NULL, 'data' => NULL],
        'backend' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to backend of application', 'ruleName' => NULL, 'data' => NULL],
        'guest' => [
            'type' => Item::TYPE_ROLE,
            'description' => 'Guest',
            'ruleName' => NULL,
            'data' => NULL
        ],
        // user that expect confirmation
        'onconfirmation' => [
            'type' => Item::TYPE_ROLE,
            'description' => 'User with email on confirmation',
            'children' => [
                'emailConfirmation'
            ],
            'ruleName' => NULL,
            'data' => NULL
        ],
        'user' => [
            'type' => Item::TYPE_ROLE,
            'description' => 'User',
            'children' => [
                'frontend'
            ],
            'ruleName' => NULL,
            'data' => NULL
        ],
        'admin' => [
            'type' => Item::TYPE_ROLE,
            'description' => 'Admin',
            'children' => [
                'frontend',
                'backend', // and also manage backendAccess
            ],
            'ruleName' => NULL,
            'data' => NULL
        ],
    ]
];
?>