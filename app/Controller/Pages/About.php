<?php
    
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page
{
    
    public static function getAbout()
    {
        
        # instância da classe organization que puxa os campos para a view
        $obOrganization = new Organization;
            
        # retorna a view da home 
        $content = View::render('pages/about', [
            'name' => $obOrganization->name, 
            'description' => 'Meu nome é Gustavo e estou fazendo esse site em PHP',
            'site' => 'Clique aqui para visitar meu LinkedIn'
        ]);
        
        # retorna a view da página (page.html)
        return parent::getPage('sobre > gustavo pereira', $content);
    }
        
}