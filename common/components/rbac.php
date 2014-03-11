<?php

use yii\rbac\Item;

return [
    // HERE ARE YOUR MANAGEMENT TASKS manageThing0 & manageThing1 - examples
    'manageThing0' => ['type' => Item::TYPE_OPERATION, 'description' => '...', 'bizRule' => NULL, 'data' => NULL],
    'manageThing1' => ['type' => Item::TYPE_OPERATION, 'description' => '...', 'bizRule' => NULL, 'data' => NULL],
    'backend' => ['type' => Item::TYPE_OPERATION, 'description' => 'access to backend of application', 'bizRule' => NULL, 'data' => NULL],

    // AND THE ROLES
    'guest' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => NULL,
        'data' => NULL
    ],
    
    'user' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'User',
        'bizRule' => NULL,
        'data' => NULL
    ],

    'admin' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Admin',
        'children' => [
            'backend', // and also manage backendAccess
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],
];

?>