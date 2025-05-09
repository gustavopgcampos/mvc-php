<?php

namespace App\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Testimony  
{
    public $id;
    public $nome;
    public $mensagem;
    public $data;
    
    #cadastra a intância atual no banco de dados
    public function cadastrar () 
    {
        #definindo a data
        $this->data = date('Y-m-d H:m:s');
        
        #insere o depoimento no banco de dados  
        $this->id=(new Database('depoimentos'))->insert([
            'nome' => $this->nome,
            'mensagem' => $this->mensagem, 
            'data'=>$this->data
        ]);
        
        return true;
    }
    
    public static function getTestimonies ($where = null, $order = null, $limit = null, $fields = '*') 
    {
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
    }
}