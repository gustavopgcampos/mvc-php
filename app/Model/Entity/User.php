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
    
    // método responsável por cadastrar a instância atual no banco de dados
    public function cadastrar () 
    {
        // insere a instância no banco de dados
        $this->id = (new Database('usuarios'))->insert([
            'nome'=> $this->nome, 
            'email'=> $this->email, 
            'senha'=> $this->senha
        ]);
        
        // retorna true caso esteja tudo certinho
        return true;
    }
    
    // método responsável por atualizar os dados no banco
    public function atualizar ()
    {
        return (new Database('usuarios'))->update('id = '.$this->id, [
            'nome'=> $this->nome, 
            'email'=> $this->email, 
            'senha'=>$this->senha
        ]);
    }
    
    // método responsável por excluir um usuário do banco
    public function excluir ()
    {
        return (new Database('usuarios'))->delete('id = '.$this ->id);
    }
    
    // método responsável por retornar uma instância com base no ID
    public static function getUserById ($id) 
    {
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }
    
    # método responsável por retornar um usuário com base no seu email
    public static function getUserByEmail($email) 
    {
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }
    
    // método responsável por retornar os usuários
    public static function getUsers ($where = null, $order = null, $limit = null, $fields = '*') 
    {
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    }
    
}
