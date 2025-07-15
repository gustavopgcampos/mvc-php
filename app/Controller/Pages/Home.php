<?php
    
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page
{ 
    public static function getHome ()
    {
        # instância da classe organization que puxa os campos para a view
        $obOrganization = new Organization;
            
        # retorna a view da home 
        $content = View::render('pages/home', [
            'name' => $obOrganization->name
        ]);
        
        
        # retorna a view da página (page.html)
        return parent::getPage('home > gustavo pereira', $content);
    }
        
}
   
