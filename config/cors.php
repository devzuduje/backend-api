<?php

return [
    /*
     * Rutas que permiten CORS
     */
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register'
    ],

    /*
     * Métodos HTTP permitidos
     */
    'allowed_methods' => ['*'],

    /*
     * Orígenes permitidos (Vue.js en puerto 3000)
     */
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:5173',  // Puerto original de Vite
        'http://localhost:5174',  // Tu puerto actual
        'http://127.0.0.1:5174',
    ],

    'allowed_origins_patterns' => [],

    /*
     * Headers permitidos
     */
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
     * Permitir cookies/credenciales entre dominios
     */
    'supports_credentials' => true,
];
