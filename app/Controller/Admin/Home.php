<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Home extends Page
{
    
    #método responsável por renderizar a view de home do painel do admin
    public static function getHome ($request) 
    {
        #conteúdo da home
        $content = View::render('admin/modules/home/index', []);
        
        #retorna a página completa
        return parent::getPanel('home admin > gustavo pereira', $content, 'home');
    }
}
