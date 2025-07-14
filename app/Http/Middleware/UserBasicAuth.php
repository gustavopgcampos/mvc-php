<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;

class UserBasicAuth {
    
    // responsável por retornar uma instância de usuário autenticado
    private function getBasicAuthUser () 
    {
        // verifica a existência dos dados de acesso
        if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW']))
        {
            return false;
        }
        
        // busca o usuário pelo email
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        
        // verifica a instância 
        if (!$obUser instanceof User)
        {
            return false;
        }
        
        // valida a senha e retorna o usuário
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
    }
    
    // método responsável por validar o acesso via HTTP basic auth
    private function basicAuth ($request) 
    {
        //verifica o usuário recebido
        if ($obUser = $this->getBasicAuthUser()) 
        {
            $request->user = $obUser;
            return true;
        }
        
        // emite o erro de senha inválida 
        throw new \Exception("Usuário Ou Senha Inválidos", 403);
    }
    
    // método responsável por executar o middleware
    public function handle ($request, $next) 
    {
        // realiza a validação do acesso via basic auth
        $this->basicAuth($request);
        
        // executa o próximo nível do middleware
        return $next($request);
    }
}
