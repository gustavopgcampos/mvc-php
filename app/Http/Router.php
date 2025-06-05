<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router {
    private $url = '';
    private $prefix = '';
    private $routes = [];
    private $request;
    private $contentType = 'text/html';
        
    public function __construct ($url) 
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }
    
    // método responsável por alterar o valor do content type
    public function setContentType($contentType) 
    {
        $this->contentType = $contentType;
    }
        
    #definir o prefixo das rotas
    private function setPrefix () 
    {
        $parseUrl = parse_url($this->url);
        
        $this->prefix = $parseUrl['path'] ?? '';
    }
    
    private function addRoute ($method, $route, $params = []) 
    {
        foreach ($params as $key=>$value)
        {
            if ($value instanceof Closure) 
            {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        
        #middlewares da rota
        $params['middlewares'] = $params['middlewares']  ?? [];
        
        #variáveis da rota
        $params['variables'] = [];
        
        $patternVariable = '/{(.*?)}/';
        
        if (preg_match_all($patternVariable, $route, $matches)) 
        {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
            
        }
        
        #PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';
        
        $this->routes[$patternRoute][$method] = $params;
    }
    
    
    #método responsável por retornar a uri desconsiderando o prefixo
    private function getUri()
    {
        $uri = $this->request->getUri();

        // remove prefixo se estiver no começo da URI
        if (strlen($this->prefix) && strpos($uri, $this->prefix) === 0) {
            $uri = substr($uri, strlen($this->prefix));
        }

        // garantir que não comece com barra
        return '/' . trim($uri, '/');
    }
    
    
    #retorna dados da rota atual
    private function getRoute () 
    {
        $uri = $this->getUri();
        
        $httpMethod = $this->request->getHttpMethod();
        
        foreach ($this->routes as $patternRoute=>$methods)
        {
            #verifica se a uri bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) 
            {
                if (isset($methods[$httpMethod])) 
                {
                    unset($matches[0]);
                    
                    $keys = $methods[$httpMethod]['variables'];

                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches); 
                    
                    $methods[$httpMethod]['variables']['request'] = $this->request; 
                    
                    return $methods[$httpMethod];
                    
                }
                #caso o método entrado não seja permitido
                throw new Exception("método não permitido", 405);
            }
        }
        #caso a url não seja encontrada
        throw new Exception("url não encontrada", 404);
    }
    
    #método responsável por executar a rota atual
    public function run () 
    {
        try {
            # obtendo a rota atual
            $route = $this->getRoute();
            
            if (!isset($route['controller'])) 
            {
                throw new Exception("Url não pode ser processada!", 500);
            }
            # argumentos da função
            $args = [];
            
            $reflection = new ReflectionFunction($route['controller']); 
            foreach ($reflection->getParameters() as $parameter) 
            {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            
            # retorna a execução da fila de middlewares
            return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);

        } catch (Exception $e) {
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }
    
    // método responsável por retornar a mensagem de erro de acordo com o content type
    private function getErrorMessage($message)
    {
        switch ($this->contentType)
        {
            case 'application/json':
                return [
                 'error'=> $message   
                ];
                break;
            default :
                return $message;
                break;
        }
    }
    
    #método responsável por redirecionar a URL
    public function redirect ($route) 
    {
        #definindo a url
        $url = rtrim($this->url, '/') . '/' . ltrim($route, '/');
        
        #executa o redirect
        header('Location: '.$url);
        
        exit;
    }
    
    #método que retorna a url atual
    public function getCurrentUrl () 
    {
        return $this->url.$this->getUri();  
    }
    
    #definir uma rota de get
    public function get ($route, $params = []) 
    {
        return $this->addRoute('GET', $route, $params);
    }
    
    public function post ($route,$params = []) 
    {
        return $this->addRoute('POST', $route, $params);
    }
    
    public function put ($route, $params = []) 
    {
        return $this->addRoute('PUT', $route, $params);
    }
    
    public function delete ($route, $params = []) 
    {
        return $this->addRoute('DELETE', $route, $params);
    }
    
    
}


//***parei 48:04***