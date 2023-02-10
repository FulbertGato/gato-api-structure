<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'success' => true,
        'message' => 'Welcome to the API',
        'data' => [
            'Service' => 'API DEFAULT STRUCTURE FOLDER',
            'version' => '1.0.0',
            'author' => 'GATO JUNIOR',
            'email' => 'contact@gatojunior.co',
            'github' => 'https://github.com/FulbertGato',
            'language' => 'French',
        ]

    ];
});
