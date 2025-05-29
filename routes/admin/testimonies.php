<?php

use \App\Http\Response;
use \App\Controller\Admin;

// rota listagem depoimentos
$obRouter-> get('/admin/testimonies', [
   'middleware' => [
       'require-admin-login'
   ], 
    function ($request) 
    {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

// rota de cadastro de um novo depoimento
$obRouter->get('/admin/testimonies/new', [
    'middleware' => [
        'require-admin-login'
    ], 
    function ($request) 
    {
        return new Response(200, Admin\Testimony::getNewTestimonies($request));
    }
]);

// rota do novo cadastro de depoimento (post)
$obRouter->post('/admin/testimonies/new', [
    'middleware' => [
        'require-admin-login'
    ], 
    function ($request) 
    {
        return new Response(200, Admin\Testimony::setNewTestimony($request));
    }
]);