<?php

return [
    'WyriHaximus' => [
        'FlyPie' => [
            'thumbnails' => [
                'adapter' => 'S3', // The adapter to use for this filesystem

                'vars' => [
                    // Array with data required to build the client, no need for it when you're providing the client or factory yourself
                ],

                // Optional, provide a set up client
                'client' => new stdClass(),

                // Optional, provide a factory that builds the client
                'factory' => function() { // Build client via callback
                    return new stdClass();
                },

                // OR

                'factory' => [$class, 'method'], // Array call user func

                // OR

                'factory' => 'Class::method', // String call user func, this is going to be interesting with the client from a event

                // OR

                'factory' => 'Vendor.Plugin.Event.Name', // Retrieve the client from an event
            ],
        ],
    ],
];
