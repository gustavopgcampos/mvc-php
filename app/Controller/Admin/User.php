<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page
{
    
    // método responsável por obter as renderizações dos itens de usuários para a página
    private static function getUserItens ($request, &$obPagination) 
    {
        #variável que irá receber os usuários
        $itens = '';
        
        #variável que define a quantidade total de registros
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        
        #definindo a página atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
        
        #instanciando a paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);
        
        #resultados da página
        $results = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit()); 
        
        #renderizar o item 
        while ($obUser = $results->fetchObject(EntityUser::class)) 
        {
            #responsável por renderizar a view
            $itens .= View::render('admin/modules/users/item', [
                'id'=> $obUser->id, 
                'nome'=>$obUser->nome,
                'email'=>$obUser->email
            ]);
        }
        
        
        #retorna os depoimentos
        return $itens;
    }
    
    // renderiza a view de listagem de usuários
    public static function getUsers ($request) 
    {
        $content = View::render('admin/modules/users/index', [
            'itens' => self::getUserItens($request, $obPagination), 
            'pagination' => parent::getPagination($request,$obPagination), 
            'status' => self::getStatus($request)
        ]);
        
        return parent::getPanel('usuários - gustavo pererira', $content, 'users');
    }
    
    // método responsável por retornar o formuário de cadastro de um novo usuário
    public static function getNewUser ($request) 
    {
        // conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
            'title' => 'Cadastrar usuário', 
            'nome' => '',
            'email' => '',
            'status'=> self::getStatus($request)
        ]);
        
        return parent::getPanel('cadastrar usuario > gustavo pererira', $content, 'users');
    }
    
    // método responsável por cadastrar um usuario no banco de dados
    public static function setNewUser ($request) 
    {
        // post vars
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';
        
        $obUser = EntityUser::getUserByEmail($email);
        
        if ($obUser instanceof EntityUser) 
        {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }
        
        // nova instância de usuário
        $obUser = new EntityUser;
        $obUser->nome = $nome;
        $obUser->email= $email;
        $obUser->senha= password_hash($senha, PASSWORD_DEFAULT);
        
        $obUser->cadastrar();
        
        // redireciona o usuário 
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
        
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
                return Alert::getSuccess('Usuário criado com sucesso'); 
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso');
                break;
            case 'deleted': 
                return Alert::getSuccess('Usuário excluido com sucesso');
                break;
            case 'duplicated':
                return Alert::getError('O E-mail digitado já está sendo utilizado');
                break;
        }
        
    }
    
    // retorna o formulário de edição de um usuário
    public static function getEditUser ($request, $id) 
    {
        // obtem o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);
        
        // valida a instância caso ela não exista
        if (!$obUser instanceof EntityUser) 
        {
            $request->getRouter()->redirect('/admin/users');
        }
        
        // conteúdo do formulário 
        $content = View::render('/admin/modules/users/form', [
            'title'=> 'Editar usuário', 
            'nome'=>$obUser->nome,
            'email'=>$obUser->email,    
            'status'=> self::getStatus($request)
        ]); 
        
        // retorna a página completa
        return parent::getPanel('editar usuário > gustavo pereira', $content, 'users');
    }
    
    // método responsável por gravar a atualização de um usuário
    public static function setEditUser ($request, $id) 
    {
        // obtem o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);
        
        // valida a instância caso ela não exista
        if (!$obUser instanceof EntityUser) 
        {
            $request->getRouter()->redirect('/admin/users');
        }
        
        // adicionar os dados que vieram dos post (postVars)
        $postVars = $request->getPostVars();
        
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';
        
        $obUserEmail = EntityUser::getUserByEmail($email);
        
        if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $id) 
        {
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }
        
        // atualiza a instância 
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($password, PASSWORD_DEFAULT);
        $obUser->atualizar();
        
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
    }
    
    // retorna o formulário de exclusão de um usuario
    public static function getDeleteUser ($request, $id) 
    {
        // obtem o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);
        
        // valida a instância caso ela não exista
        if (!$obUser instanceof EntityUser) 
        {
            $request->getRouter()->redirect('/admin/users');
        }
        
        $content = View::render('admin/modules/users/delete', [
            'nome'=>$obUser->nome, 
            'email'=>$obUser->email
        ]);
        
        return parent::getPanel('excluir usuário - gustavo pereira', $content, 'users');
    }
    
    // método responsável por excluir um usuario
    public static function setDeleteUser ($request, $id) 
    {
        // obtem o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);
        
        // valida a instância caso ela não exista
        if (!$obUser instanceof EntityUser) 
        {
            $request->getRouter()->redirect('/admin/users');
        }
        
        // exclui o usuario
        $obUser->excluir();
        
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}