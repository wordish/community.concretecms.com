<?php

return [
    'query($cursor: String, $owner: String!, $name: String!)' => [
        'repository(owner: $owner, name: $name)' => [
            'issues(states: [OPEN, CLOSED], orderBy: {field: CREATED_AT, direction: DESC}, last: 100, before: $cursor)' => [
                'nodes' => [
                    'createdAt',
                    'state',
                    'author' => [
                        'login',
                    ],
                    'repository' => [
                        'nameWithOwner',
                    ],
                ],
                'pageInfo' => [
                    'cursor:startCursor',
                    'hasMore:hasPreviousPage'
                ],
            ],
        ],
    ],
];
