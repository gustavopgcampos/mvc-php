<?php

namespace App\Http;

class Request {
    private $router; #instância do router
    private $httpMethod;
    private $uri;
    private $queryParams = [];
    private $postVars = [];
    private $headers = [];
    
    public function __construct ($router) 
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
    }
    
    // método responsável por definir as variáveis do post
    private function setPostVars() 
    {
        // verifica o método da requisição
        if ($this->httpMethod == 'GET') return false;
        
        // post padrão
        $this->postVars = $_POST ?? [];
        
        //post json
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }
    
    #método responsável por definir a URI
    private function setUri ()
    {
        #define a URI completa com gets 
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        
        #remover os gets da URI
        $xURI = explode('?', $this->uri);
        
        #atribui a uri somente a rota dela removendo totalmente os get da URL
        $this->uri = $xURI[0];
    }
    
    #método que retorna a instância de router
    public function getRouter () 
    {
        return $this->router;
    }
    
    #método que retorna o método HTTP
    public function getHttpMethod () 
    {
        return $this->httpMethod;
    }
    
    public function getUri ()
    {
        return $this->uri;
    }
            
    public function getQueryParams () 
    {
        return $this->queryParams;
    }
    
    public function getPostVars () 
    {
        return $this->postVars;
    }
    
    public function getHeaders () 
    {
        return $this->headers;
    }
        
}
