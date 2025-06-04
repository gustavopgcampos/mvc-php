<?php

use \App\Http\Response;
use \App\Controller\Admin;

// rota listagem usuários
$obRouter-> get('/admin/users', [
   'middleware' => [
       'require-admin-login'
   ], 
    function ($request) 
    {
        return new Response(200, Admin\User::getUsers($request));
    }
]);

// rota de cadastro de um novo usuário
$obRouter->get('/admin/users/new', [
    'middleware' => [
        'require-admin-login'
    ], 
    function ($request) 
    {
        return new Response(200, Admin\User::getNewUser($request));
    }
]);

// rota de edição de um usuário
$obRouter->get ('/admin/users/{id}/edit', [
   'middleware' => [
      'require-admin-login' 
   ], 
   function ($request, $id) 
   {
        return new Response(200, Admin\User::getEditUser($request, $id));
   }
]);

// rota de exclusão de um usuário
$obRouter->get ('/admin/users/{id}/delete', [
   'middleware' => [
      'require-admin-login' 
   ], 
   function ($request, $id) 
   {
        return new Response(200, Admin\User::getDeleteUser($request, $id));
   }
]);

// rota de exclusão de um usuário
$obRouter->post ('/admin/users/{id}/delete', [
   'middleware' => [
      'require-admin-login' 
   ], 
   function ($request, $id) 
   {
        return new Response(200, Admin\User::setDeleteUser($request, $id));
   }
]);

// rota do novo cadastro do usuário (post)
$obRouter->post('/admin/users/new', [
    'middleware' => [
        'require-admin-login'
    ], 
    function ($request) 
    {
        return new Response(200, Admin\User::setNewUser($request));
    }
]);

// rota de edição de um usuário (post)
$obRouter->post('/admin/users/{id}/edit', [
    'middleware' => [
        'require-admin-login'
    ],
    function ($request, $id) 
    {
        return new Response(200, Admin\User::setEditUser($request, $id));
    }
]);