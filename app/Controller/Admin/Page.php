<?php
namespace App\Controller\Admin;

use \App\Utils\View;

class Page 
{
    // módulos disponíveis no painel
    private static $modules = [
        'home'=> [
            'label'=> 'Home', 
            'link'=> URL.'/admin'
        ],
        'testimonies'=> [
            'label'=> 'Depoimentos', 
            'link'=> URL.'/admin/testimonies'
        ], 
        'users'=> [
            'label'=> 'Usuários', 
            'link'=> URL.'/admin/users'
        ]
    ];
    
    #método que retorna a view do admin
    public static function getPage ($title, $content) 
    {
        return View::render('admin/page', [
            'title'=> $title,
            'content'=>$content
        ]);
    }
    
    // método responsável por renderizar a view do menu do painel
    private static function getMenu ($currentModule) 
    {
        // links do menu    
        $links = '';
        
        // iteração dos módulos
        foreach (self::$modules as $hash=>$module)
        {
            $isCurrent = $hash == $currentModule;
            
            $links .= View::render('admin/menu/link', [
                'label'=>$module['label'], 
                'link'=>$module['link'], 
                'current'=> $isCurrent ? 'active text-danger' : ''
            ]);
        }
        
        // retorna a renderização do menu
        return View::render('admin/menu/box', [
            'links'=> $links
        ]);
    }
    
    #método responsável por renderizar a view do painel com conteúdos dinâmicos
    public static function getPanel ($title, $content, $currentModule) 
    {
        #renderiza a view do painel     
        $contentPanel = View::render('admin/panel', [
           'menu' => self::getMenu($currentModule), 
            'content' => $content
        ]);
        
        #retorna a página renderizada
        return self::getPage($title, $contentPanel);
    }
    
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
            
            $links .= View::render('admin/pagination/link', [
            'page' => $page['page'], 
            'link' => $link,
            'active' => $page['current'] ?  'active' : ''
            ]);
        }
        
        #renderiza a box de paginação
        return View::render('admin/pagination/box', [
                'links' => $links
        ]);
    }
}
