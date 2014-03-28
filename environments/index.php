<?php

/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'writable' => [
 *             // list of directories that should be set writable
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'writable' => [
            "common/web/css",
            "backend/runtime",
            "backend/web/assets",
            "backend/web/css",
            "console/runtime",
            "console/migrations",
            "frontend/runtime",
            "frontend/web/assets",
            "frontend/web/css",
            "frontend/web/userimages"
        // handled by composer.json already
        ],
        'executable' => [
            'yii',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'writable' => [
            "common/web/css",
            "backend/runtime",
            "backend/web/assets",
            "backend/web/css",
            "console/runtime",
            "console/migrations",
            "frontend/runtime",
            "frontend/web/assets",
            "frontend/web/css",
            "frontend/web/userimages"
        // handled by composer.json already
        ],
        'executable' => [
            'yii',
        ],
    ],
];
