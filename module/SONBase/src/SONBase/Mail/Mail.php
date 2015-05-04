<?php

namespace SONBase\Mail;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class Mail {

    protected $transport;
    protected $view;
    protected $body;
    protected $message;
    protected $subject;
    protected $to;
    protected $data;
    protected $page;

    /**
     * 
     * @param SmtpTransport $transport
     * @param type $view
     * @param type $page
     */
    function __construct(SmtpTransport $transport, $view, $page) {
        $this->transport = $transport;
        $this->view = $view;
        $this->page = $page;
    }

    /**
     * Seta o assunto e retorna o objeto para termos uma interface fluente
     * @param type $subject
     * @return \SONBase\Mail\Mail
     */
    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * Seta o destinatario e retorna o objeto para termos uma interface fluente
     * @param type $to
     * @return \SONBase\Mail\Mail
     */
    public function setTo($to){
        $this->to = $to;
        return $this;
    }
    
    /**
     * Seta os dados e retorna o objeto para termos uma interface fluente
     * @param type $data
     * @return \SONBase\Mail\Mail
     */
    public function setData($data){
        $this->data = $data;
        return $this;
    }
    
    /**
     * Renderiza a ViewModel com o template, opções e as variaveis
     * @param type $page
     * @param array $data
     * @return type
     */
    public function renderView($page, array $data){
        $model = new ViewModel();
        $model->setTemplate("mailer/{$page}.phtml");
        $model->setOption("has_parent", true);
        $model->setVariables($data);        
        return $this->view->render($model);
    }
    
    /**
     * Prepara o email com as configurações e a mensagem.
     * @return \SONBase\Mail\Mail
     */
    public function prepare(){
        $html = new MimePart($this->renderView($this->page, $this->data));
        $html->type = "text/html";
        
        $body = new MimeMessage();
        $body->setParts(array($html));
        $this->body = $body;
        $config = $this->transport->getOptions()->toArray();   
        
        $this->message = new Message;        
        $this->message
        ->addFrom($config['connection_config']['from'])
        ->addTo($this->to)
        ->setSubject($this->subject)
        ->setBody($this->body);
        
        return $this;
    }
    
    /**
     * Envia o email.
     */
    public function send(){
        $this->transport->send($this->message);
    }

}
