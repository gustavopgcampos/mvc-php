<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page 
{
    #método que retorna a view do admin
    public static function getPage ($title, $content) 
    {
        return View::render('admin/page', [
            'title'=> $title,
            'content'=>$content
        ]);
    }
    
}
