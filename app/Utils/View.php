<?php

namespace App\Utils;

class View {
    #variáveis padrões da view  

    private static $vars = [];

    #método responsável por definir os dados iniciais da classe

    public static function init($vars = []) {
        self::$vars = $vars;
    }

    # retorna o conteúdo da view

    private static function getContentView($view) {
        $file = __DIR__ . '/../../resources/view/' . $view . '.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    # retorna o conteúdo renderizado da view

    public static function render($view, $vars = []) {
     
        $vars = array_merge(self::$vars, $vars);
        
        #unindo as variáveis da view 
        $keys = array_keys($vars);

        $keys = array_map(function ($item) {
            return '{{' . $item . '}}'; # concatena {{}} dentro do item 
        }, $keys);

        $contentView = self::getContentView($view);

        #retorna o conteúdo renderizado
        return str_replace($keys, array_values($vars), $contentView);
    }
}
