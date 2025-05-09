<?php

use \App\Http\Response;
use \App\Controller\Admin;

#rota admin
$obRouter-> get('/admin', [
    function ()
    {
        return new Response(200,"admin :)");
    }
]);

#rota admin login
$obRouter-> get('/admin/login', [
    function ($request)
    {
        return new Response(200,Admin\Login::getLogin($request));
    }
]);

#rota de login post
$obRouter-> post('/admin/login', [
    function ($request)
    {
        return new Response(200,Admin\Login::setLogin($request));
    }
]);