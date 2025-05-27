<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

//bloqueio de acesso para usuários que não estão logados
class RequireAdminLogin 
{
    public function handle ($request, $next) 
    {
        if (!SessionAdminLogin::isLogged())
        {
            $request->getRouter()->redirect('/admin/login');
        }
        
        return $next($request);
    }
}
