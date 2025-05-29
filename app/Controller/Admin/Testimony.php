<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{
    
    private static function getTestimonyItens ($request, &$obPagination) 
    {
        #variável que irá receber os depoimentos
        $itens = '';
        
        #variável que define a quantidade total de registros
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        
        #definindo a página atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
        
        #instanciando a paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);
        
        #resultados da página
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit()); 
        
        #renderizar o item 
        while ($obTestimony = $results->fetchObject(EntityTestimony::class)) 
        {
            #responsável por renderizar a view
            $itens .= View::render('admin/modules/testimonies/item', [
                'id'=> $obTestimony->id, 
                'nome'=>$obTestimony->nome,
                'mensagem'=>$obTestimony->mensagem,
                'data'=>date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }
        
        
        #retorna os depoimentos
        return $itens;
    }
    
    // renderiza a view de listagem de depoimentos
    public static function getTestimonies ($request) 
    {
        $content = View::render('admin/modules/testimonies/index', [
            'itens' => self::getTestimonyItens($request, $obPagination), 
            'pagination' => parent::getPagination($request,$obPagination)
        ]);
        
        return parent::getPanel('depoimentos - gustavo pererira', $content, 'testimonies');
    }
    
    // método responsável por retornar o formuário de cadastro de um novo depoimento
    public static function getNewTestimonies ($request) 
    {
        // conteúdo do formulário
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Cadastrar depoimento'
        ]);
        
        return parent::getPanel('cadastrar depoimento - gustavo pererira', $content, 'testimonies');
    }
    
    // método responsável por cadastrar um depoimento no banco de dados
    public static function setNewTestimony ($request) 
    {
        //post vars
        $postVars = $request->getPostVars();
        
        //***parei 1:01:26***
    }
    
}