<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User 
{
    #campos que o usuário tem dentro do banco de dados
    public $id;
    public $nome;
    public $email;
    public $senha;
    
    # método responsável por retornar um usuário com base no seu email
    public static function getUserByEmail($email) 
    {
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class); 
    }
    
    // método responsável por retornar os usuários
    public static function getUsers ($where = null, $order = null, $limit = null, $fields = '*') 
    {
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    }
    
}
