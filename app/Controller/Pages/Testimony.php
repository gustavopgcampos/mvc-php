<?php
    
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{
    #& é uma referência de memória que faz com que toda alteração feita em obPagination ocorra tambpém onde é puxado
    
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
            $itens .= View::render('pages/testimony/item', [
                'nome'=>$obTestimony->nome,
                'mensagem'=>$obTestimony->mensagem,
                'data'=>date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }
        
        
        #retorna os depoimentos
        return $itens;
    }
    
    public static function getTestimonies($request)
    {
        # instância da classe organization que puxa os campos para a view
            
        # retorna a view de depoimentos 
        $content = View::render('pages/testimonies', [
            'itens'=> self::getTestimonyItens($request, $obPagination), 
            'pagination'=> parent::getPagination($request, $obPagination)
        ]); 
        
        
        # retorna a view da página (page.html)
        return parent::getPage('depoimentos > gustavo pereira', $content);
    }
    
    #método responsável por cadastrar um depoimento
    public static function insertTestimony($request)
    {
        #instância do request que retorna os dados do post
        $postVars = $request->getPostVars();
        
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar(); 
        
        return self::getTestimonies($request);
    }
        
}
   
