<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout 
{
    
    #método responsável por executar o middleware
    public function handle ($request, $next) 
    {
        #verifica se o usuário está logado
        if (SessionAdminLogin::isLogged()) 
        {
            $request->getRouter()->redirect('/admin');
        }
        
        return $next($request);
    }
}