<?php

date_default_timezone_set('America/Sao_Paulo');
require __DIR__.'/includes/app.php';

use \App\Http\Router;   # instância de router

#inicia o router
$obRouter = new Router(URL);

#inclui as rotas de páginas
include __DIR__.'/routes/pages.php';

#inclui as rotas do painel do admin 
include __DIR__.'/routes/admin.php';

#imprime o response da rota
$obRouter->run()->sendResponse(); 

