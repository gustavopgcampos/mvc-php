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

// rota de edição de um depoimento 
$obRouter->get ('/admin/testimonies/{id}/edit', [
   'middleware' => [
      'require-admin-login' 
   ], 
   function ($request, $id) 
   {
        return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
   }
]);

// rota de exclusão de um depoimento
$obRouter->get ('/admin/testimonies/{id}/delete', [
   'middleware' => [
      'require-admin-login' 
   ], 
   function ($request, $id) 
   {
        return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
   }
]);

// rota de exclusão de um depoimento
$obRouter->post ('/admin/testimonies/{id}/delete', [
   'middleware' => [
      'require-admin-login' 
   ], 
   function ($request, $id) 
   {
        return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
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

// rota de edição de um depoimento (post)
$obRouter->post('/admin/testimonies/{id}/edit', [
    'middleware' => [
        'require-admin-login'
    ],
    function ($request, $id) 
    {
        return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
    }
]);