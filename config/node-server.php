<?php

$baseUrl = env('NODE_SERVER_URL', 'http://127.0.0.1:3000');

return [
    'url' => [
        'html_to_pdf' => $baseUrl . '/html_to_pdf'
    ]
];
