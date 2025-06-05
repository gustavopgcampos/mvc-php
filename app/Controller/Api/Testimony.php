<?php

namespace App\Controller\Api;

use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api{
    
    // método responsável por retornar os depoimentos cadastrados
    public static function getTestimonies ($request) 
    {
        return [
            'depoimentos' => self::getTestimonyItems($request, $obPagination), 
            'paginacao' => parent::getPagination($request, $obPagination)
        ]; 
    }
    
    private static function getTestimonyItems ($request, &$obPagination)
    {
        $itens = [];
        
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
        
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);
        
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());
        
        while ($obTestimony = $results->fetchObject(EntityTestimony::class))
        {
            $itens[] = [
                'id' => $obTestimony->id, 
                'nome'=>$obTestimony->nome, 
                'mensagem' => $obTestimony->mensagem, 
                'data' => $obTestimony->data
            ];
        }
        
        return $itens;
    }

    // método responsável por retornar os detalhes de um depoimento
    public static function getTestimony ($request, $id) 
    {
        // buscando depoimentos
        $obTestimony = EntityTestimony::getTestimonyById($id);
        
        // valida se o depoimento existe 
        if (!$obTestimony instanceof EntityTestimony) 
        {
            throw new \Exception("O depoimento ".$id." não foi encontrado.", 404);
        }
    }
    
}
