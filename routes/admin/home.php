<?php

use \App\Http\Response;
use \App\Controller\Admin;

#rota 'home' admin
$obRouter-> get('/admin', [
    'middlewares' => [
        'required-admin-login'
    ],

    function ($request)
    {
        return new Response(200,Admin\Home::getHome($request));
    }
]);

