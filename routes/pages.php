<?php

use \App\Http\Response;
use \App\Controller\Pages;

#rota de home
$obRouter-> get('/', [
    function ()
    {
        return new Response(200, Pages\Home::getHome());
    }
]);

#rota de sobre 
$obRouter-> get('/sobre', [
    function ()
    {
        return new Response(200, Pages\About::getAbout());
    }
]);

#rota de depoimentos
$obRouter->get('/depoimentos', [
   function ($request) 
    {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

#rota que insere os depoimentos
$obRouter->post('/depoimentos', [
   function ($request) # instância do request 
    {   
        
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);

#para criar uma rota basta copiar o código acima, o que muda é que entre as aspas simples
#tera a uri para acessar e nos casos a cima são passados controladores que dão acessos as
#paginas.E depois também é necessário incluir o arquivo no index para ter acesso