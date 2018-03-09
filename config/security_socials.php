<?php

return [

    'providers' => [

        'github' => [
            'scopes' => [],
            //'transformer' => \StephBug\SecuritySocial\User\UserTransformer::class
        ]
    ],

    'transformer' => \StephBug\SecuritySocial\User\UserTransformer::class
];