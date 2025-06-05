<?php

namespace App\Controller\Api;

class Api {
    
    // método responsável por retornar os detalhes da API
    public static function getDetails ($request) 
    {
        return [
            'nome'=> 'API - gustavo pereira',
            'versao' => 'v1.0.0', 
            'autor' => 'gustavo pereira', 
            'email'=> 'gustavopgcampos@gmail.com'
        ]; 
    }
    
    // método responsável por retornar os detalhes da paginação
    protected static function getPagination ($request, $obPagination) 
    {
        // obtendo os queryparams
        $queryParams = $request->getQueryParams();
        
        // obtendo as páginas 
        $pages = $obPagination->getPages();
        
        // retorno dos dados
        return [
          'paginaAtual'=> isset($queryParams['page']) ? (int)$queryParams['page'] : 1, 
          'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }
}
