<?php

namespace App\Http;

class Response 
{
    private $httpCode = 200;
    private $headers = [];
    private $contentType = 'text/html';
    private $content;
    
    #inica a classe e define os valores
    public function __construct ($httpCode, $content, $contentType = 'text/html') 
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }
    
    #método que altera o content type do response   
    public function setContentType ($contentType) 
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }
    
    #método que adiciona um registro no cabeçalho de reponse
    public function addHeader ($key, $value) 
    {
        $this->headers[$key] =  $value;
    }
    
    #envia os headers para o navegador
    private function sendHeaders () 
    {
        #setta o http code
        http_response_code($this->httpCode);
        
        #envia os headers
        foreach ($this->headers as $key=>$value) 
        {
            header($key.': '.$value);
        }
    }
    
    #metodo responsavel por enviar a responsta para o usuario
    public function sendResponse () 
    {
        #enviar os headers
        $this->sendHeaders();
        
        #imprime o conteúdo
        switch ($this->contentType) 
        {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }
    
}
