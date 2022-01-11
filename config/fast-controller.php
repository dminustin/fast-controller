<?php

return [
    'create_route' => true,
    'create_controller' => true,
    'uri_prefix' => '/',//Must start with '/'
    'controller_path' => 'Http/Controllers/Generated/',//Must have "/" at the end
    'namespace' => 'Http\\Controllers\\Generated\\',//Must have "\\" at the end
    'override_existing' => false,
    'router_name' => 'fc-routes.php',
    'views_path' => 'vendor/fast-controller',
    'default' => [
        'available_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'STORE'],
        'method' => 'POST',//POST|GET|PUT...
        'need_auth' => true,//By default, user must be logged in
        'auth_middlewares' => ['auth'],
        'available_params'=>['string','integer','boolean','array','file','email'],
        'param'=>'string'
    ]
];
