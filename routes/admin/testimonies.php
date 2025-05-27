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