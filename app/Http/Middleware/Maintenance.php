<?php

namespace App\Http\Middleware;

class Maintenance 
{
    #método responsável por executar o middleware
    public function handle ($request, $next)
    {
        #verifica o estado de manutenção da página, neste caso se manutenção estiver true, retorna a exception 
        if (getenv('MAINTENANCE') == 'true')
        {
            throw new \Exception("Página em manutenção, tente novamente mais tarde!", 200);
        }
        
        #executa o próximo nível do middleware
        return $next($request);
    }
}
