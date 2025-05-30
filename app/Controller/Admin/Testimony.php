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
            'title' => 'Cadastrar depoimento', 
            'nome' => '', 
            'mensagem'=> '', 
            'status'=>''
        ]);
        
        return parent::getPanel('cadastrar depoimento > gustavo pererira', $content, 'testimonies');
    }
    
    // método responsável por cadastrar um depoimento no banco de dados
    public static function setNewTestimony ($request) 
    {
        // post vars
        $postVars = $request->getPostVars();
        
        // nova instância de depoimento
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'] ?? '';
        $obTestimony->mensagem = $postVars['mensagem'] ?? '';
        $obTestimony->cadastrar();
        
        // redireciona o usuário 
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
        
    }
    
    // método responsável por retornar a mensagem de status
    private static function getStatus ($request)
    {
        // query params
        $queryParams = $request->getQueryParams();
        
        if (!isset($queryParams['status'])) return '';
        
        switch ($queryParams['status'])
        {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso'); 
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso');
                break;
        }
        
    }
    
    // retorna o formulário de edição de um depoime
    public static function getEditTestimony ($request, $id) 
    {
        // obtem o depoimento do banco de dados
        $obTestimony = EntityTestimony::getTestimonyById($id);
        
        // valida a instância caso ela não exista
        if (!$obTestimony instanceof EntityTestimony) 
        {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        // conteúdo do formulário 
        $content = View::render('/admin/modules/testimonies/form', [
            'title'=> 'Editar depoimento', 
            'nome'=>$obTestimony->nome, 
            'mensagem'=>$obTestimony->mensagem, 
            'status'=> self::getStatus($request)
        ]); 
        
        // retorna a página completa
        return parent::getPanel('editar depoimentos > gustavo pereira', $content, 'testimonies');
    }
    
    // método responsável por gravar a atualização de um depoimento 
    public static function setEditTestimony ($request, $id) 
    {
        // obtem o depoimento do banco de dados
        $obTestimony = EntityTestimony::getTestimonyById($id);
        
        // valida a instância caso ela não exista
        if (!$obTestimony instanceof EntityTestimony) 
        {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        // adicionar os dados que vieram dos post (postVars)
        $postVars = $request->getPostVars();
        
        // atualiza a instância 
        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->atualizar();
        
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
    }
    
    // retorna o formulário de exclusão de um depoimento
    public static function getDeleteTestimony ($request, $id) 
    {
        $obTestimony = EntityTestimony::getTestimonyById($id);
        
        if (!$obTestimony instanceof EntityTestimony)
        {
            $request->getRouter->redirect('/admin/testimonies');
        }
        
        $content = View::render('admin/modules/testimonies/delete', [
            'nome'=>$obTestimony->nome, 
            'mensagem'=>$obTestimony->mensagem
        ]);
        
        return parent::getPanel('excluir depoimento - gustavo pereira', $content, 'testimonies');
    }
    
    // método responsável por excluir um depoimento
    public static function setDeleteTestimony ($request, $id) 
    {
        // obtem o depoimento do banco de dados
        $obTestimony = EntityTestimony::getTestimonyById($id);
        
        // valida a instância caso ela não exista
        if (!$obTestimony instanceof EntityTestimony) 
        {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        // exclui o depoimento --> parei 1:23:03
        $obTestimony->excluir();
        
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
    }
}