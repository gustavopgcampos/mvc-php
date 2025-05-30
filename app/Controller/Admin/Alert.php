<?php
namespace App\Controller\Admin;

use \App\Utils\View;

class Alert 
{
    
    #método responsável por retornar uma mensagem de sucesso
    public static function getSuccess ($message) 
    {
        return View::render('admin/alert/status', [
            'tipo'=>'success', 
            'mensagem'=>$message
        ]);
    }
    
    public static function getError ($message) 
    {
        return View::render('admin/alert/status', [
           'tipo'=>'danger', 
            'mensagem'=>$message
        ]);
    }
}
