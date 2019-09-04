<?php

return [
    'query' => [
        'organization(login: "concrete5")' => [
            'repositories(last: 100)' => [
                'totalCount',
                'nodes' => [
                    'nameWithOwner'
                ]
            ]
        ]
    ]
];
