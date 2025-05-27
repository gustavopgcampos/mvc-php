<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Testimony extends Page
{
    // renderiza a view de listagem de depoimentos
    public static function getTestimonies ($request) 
    {
        $content = View::render('admin/modules/testimonies/index', []);
        
        return parent::getPanel('depoimentos - gustavo pererira', $content, 'testimonies');
    }
    
}