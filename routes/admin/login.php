<?php

use \App\Http\Response;
use \App\Controller\Admin;

#guarda todas as rotas relacionadas ao login do administrador

#rota admin login 'get'
$obRouter->get('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ], 

    function ($request)
    {
        return new Response(200,Admin\Login::getLogin($request));
    }
]);

#rota de login post 'post'
$obRouter-> post('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ], 

    function ($request)
    {
        return new Response(200,Admin\Login::setLogin($request));
    }
]);

#rota de logout
$obRouter -> get('/admin/logout', [
    'middlewares' => [
        'required-admin-login'
    ],

    function ($request) 
    {
        return new Response(200, Admin\Login::setLogout($request));
    }
]);
