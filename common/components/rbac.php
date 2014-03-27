<?php

use yii\rbac\Item;

return [
    // HERE ARE YOUR MANAGEMENT TASKS manageThing0 & manageThing1 - examples
    'emailConfirmation' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to email confirmation page', 'bizRule' => NULL, 'data' => NULL],
    'frontend' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to frontend of application', 'bizRule' => NULL, 'data' => NULL],
    'backend' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to backend of application', 'bizRule' => NULL, 'data' => NULL],

    'guest' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => NULL,
        'data' => NULL
    ],
    // user that expect confirmation
    'onconfirmation' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'User with email on confirmation',
        'children' => [
            'emailConfirmation'
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],
    
    'user' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'User',
        'children' => [
           'frontend'  
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],

    'admin' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Admin',
        'children' => [
            'frontend',
            'backend', // and also manage backendAccess
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],
];

?>