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
        $this->data = date('Y-m-d H:i:s');
        
        #insere o depoimento no banco de dados  
        $this->id=(new Database('depoimentos'))->insert([
            'nome' => $this->nome,
            'mensagem' => $this->mensagem, 
            'data'=>$this->data
        ]);
        
        return true;
    }
    
    // método responsável por atualizar os dados do banco com os dados da instância atual
    public function atualizar () 
    {
        // atualiza os depoimentos no banco de dados
        return (new Database('depoimentos'))->update('id = '.$this->id, [
            'nome' => $this->nome, 
            'mensagem' => $this->mensagem
        ]);
    }
    
    // método responsável por retornar um depoimento com base no seu id
    public static function getTestimonyById ($id)
    {
        return self::getTestimonies('id = '.$id)->fetchObject(self::class);
    }
    
    public static function getTestimonies ($where = null, $order = null, $limit = null, $fields = '*') 
    {
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
    }
}