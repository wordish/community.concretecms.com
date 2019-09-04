<?php

return [
    'query($cursor: String, $owner: String!, $name: String!)' => [
        'repository(owner: $owner, name: $name)' => [
            'pullRequests(states: [OPEN, CLOSED, MERGED], orderBy: {field: CREATED_AT, direction: DESC}, last: 100, before: $cursor)' => [
                'nodes' => [
                    'merged',
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
