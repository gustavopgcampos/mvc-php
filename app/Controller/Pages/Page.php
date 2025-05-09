<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page {
    #método responsável por renderizar o topo da pagina 
    private static function getHeader () 
    {
        return View::render('pages/header', [
            'URL' => URL
        ]);
    }
    
    private static function getFooter () 
    {
        return View::render('pages/footer');
    }
    
    #método responsável por renderizar o layout de paginação
    public static function getPagination ($request, $obPagination)
    {
        #pages recebe as páginas e define qual é a página atual
        $pages = $obPagination->getPages();
        
        #verifica a quantidade de páginas e se o número da página for menor que 1 irá retornar string vazia
        if (count($pages) <= 1) 
        {
            return '';
        }
        
        $links = '';
        
        #obter a url atual do projeto sem gets
        $url = $request->getRouter()->getCurrentUrl();
        
        #get da página
        $queryParams = $request->getQueryParams();
        
        #renderiza os links
        foreach ($pages as $page) 
        {            
            #altera a página
            $queryParams['page'] = $page['page'];
            
            #linka o local da requisição com o queryparams para ficar um link normal
            $link = $url.'?'.http_build_query($queryParams);
            
            $links .= View::render('pages/pagination/link', [
            'page' => $page['page'], 
            'link' => $link,
            'active' => $page['current'] ?  'active' : ''
            ]);
        }
        
        #renderiza a box de paginação
        return View::render('pages/pagination/box', [
                'links' => $links
        ]);
    }
    
    #metodo que retorna o conteudo da pagina (page.html) 
    public static function getPage ($title, $content) 
    {
        return View::render('pages/page', [
            'title' => $title, 
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }
    
}