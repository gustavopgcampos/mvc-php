<?php

use \App\Http\Response;
use \App\Controller\Api;

// rota de listagem de usuários
$obRouter->get('/api/v1/users', [
   'middlewares' => [
     'api'  
   ],
   function ($request) 
    {
        return new Response(200,Api\Testimony::getTestimonies($request), 'application/json');
    }
    
]);

// rota de consulta individual de usuários
$obRouter->get('/api/v1/users/{id}', [
   'middlewares' => [
       'api'
   ], 
   function ($request, $id) 
   {
    return new Response(200, Api\Testimony::getTestimony($request, $id), 'application/json');
   }
]);

//rota de cadastro de usuários
$obRouter->post('/api/v1/users', [
    'middlewares' => [
        'api', 
//        'user-basic-auth'
    ], 
    function ($request)
    {
        return new Response(201,Api\Testimony::setNewTestimony($request), 'application/json');
    }
]);

// rota de atualização de usuários
$obRouter->put('/api/v1/users/{id}', [
    'middlewares' => [
        'api', 
//        'user-basic-auth'
    ], 
    function ($request, $id)
    {
        return new Response(200,Api\Testimony::setEditTestimony($request, $id), 'application/json');
    }
]);

// rota de remoção de usuários
$obRouter->delete('/api/v1/users/{id}', [
    'middlewares' => [
        'api', 
//        'user-basic-auth'
    ], 
    function ($request, $id)
    {
        return new Response(200,Api\Testimony::setDeleteTestimony($request, $id), 'application/json');
    }
]);