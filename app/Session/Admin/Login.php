<?php

namespace App\Session\Admin;

class Login 
{
    
    #método responsável por iniciar a sessão
    private static function init() 
    {
        #verifica se a sessão não está ativa (se o status da sessão for diferente de ativo, então a sessão é iniciada)
        if (session_status() != PHP_SESSION_ACTIVE) 
        {
            session_start();
        }
    }
    
    #método responsável por criar o login do usuário
    public static function login ($obUser)
    {
        #inicia a sessão
        self::init();
        
        #define a sessão do usuário 
        $_SESSION['admin']['usuario'] = 
        [
            'id' => $obUser->id, 
            'nome'=>$obUser->nome, 
            'email'=>$obUser->email 
        ];
        
        #retorna que a sessão foi criada com sucesso 
        return true;
    }
    
    #método responsável por verificar se o usuário está logado (return true or false)
    public static function isLogged () 
    {
        self::init();
        
        #retorna a verificação
        return isset($_SESSION['admin']['usuario']['id']);
    }
    
    #método responsável por executar o logout do usuário 
    public static function logout () 
    {
        self::init();
        
        unset($_SESSION['admin']['usuario']);
        
        return true;
    }
}
