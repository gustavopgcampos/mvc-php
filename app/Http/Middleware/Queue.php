<?php

namespace App\Http\Middleware;

class Queue 
{
    #mapeamento de middlewares que serão carregados em todas as rotas
    private static $default = [];
    
    #mapeamento de middewares
    private static $map = [];
    
    #fila de middlewares a serem executados
    private $middlewares = [];
    
    #função de execução do controlador
    private $controller;
    
    #argumentos da função do controlador
    private $controllerArgs = [];
    
    #método responsável por construir a classe de fila dos middlewares
    public function __construct ($middlewares, $controller, $controllerArgs) 
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }
    
    #método responsável por definir o mapeamento de middlewares
    public static function setMap ($map)
    {
        self::$map = $map;
    }
    
    public static function setDefault ($default) 
    {
        static::$default = $default;
    }
    
    #método responsável por executar o próximo nível da fila de middlewares
    public function next ($request) 
    {
        #verifica se a fila está vazia
        if (empty($this->middlewares)) return call_user_func_array ($this->controller, $this->controllerArgs);
        
        $middleware = array_shift($this->middlewares);
        
        #verifica o mapeamento do middleware
        if (!isset(self::$map[$middleware])) 
        { 
            throw new \Exception("Problemas Ao Processar o Middleware da Requisição", 500);
        }
        
        #criando função de next
        $queue = $this;
        $next = function($request) use ($queue)
        {
            return $queue->next($request);
        };
        
        #executa o middleware
        return (new self::$map[$middleware])->handle($request, $next);
        
    }
    
}
