<?php

namespace App\COntroller\Admin;

use App\Utils\View;
use App\Model\Entity\User;

class Login extends Page
{
    #método responsável por retornar a renderização da página de login
    public static function getLogin ($request, $errorMessage = null) 
    {
        $status = !is_null($errorMessage) ? View::render('admin/login/status', [
            'mensagem' => $errorMessage
        ]) : '';
            
        #conteúdo da página de login
        $content = View::render('admin/login', [
            'status'=>$status,
            
        ]);
        
        return parent::getPage('login > gustavo pereira', $content);
    }
    
    #método responsável por definir o login do usuário
    public static function setLogin ($request) 
    {
        #recebe o que foi digitado no login
        $postVars = $request->getPostvars();
        $email = $postVars['email'] ?? '';  
        $senha = $postVars['senha'] ?? '';
        
        #busca usuário pelo email
        $obUser = User::getUserByEmail($email);
        
        if (!$obUser instanceof User) 
        {
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }
        
        #verifica a senha do usuário
        if (!password_verify($senha, $obUser->senha)) 
        {
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }
        
    }
}

#parei 33:25;
