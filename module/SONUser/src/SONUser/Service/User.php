<?php

namespace SONUser\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use SONBase\Mail\Mail;

class User extends AbstractService{
    
    protected $transport;
    protected $view;

    function __construct(EntityManager $em, SmtpTransport $transport, $view) {
        
        parent::__construct($em);
        
        $this->entity = "SONUser\Entity\User";
        $this->transport = $transport;
        $this->view = $view;
            
    }
    
    public function insert(array $data){
        
        $entity = parent::insert($data);
        
        $dataEmail = array('nome' => $data['nome'], 'activationkey' => $entity->getActivationKey());
        
        if($entity){
            $mail = new Mail($this->transport, $this->view, 'add-uer');
            $mail->setSubject('Confirmação de cadastro')
                 ->setTo($data['email'])
                 ->setData($dataEmail)
                 ->prepare()
                 ->send();
            
            return $entity;
            
        }
    }

    
}