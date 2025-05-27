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
    
    //***parei 18:26***
}
