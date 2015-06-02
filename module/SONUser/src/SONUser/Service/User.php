<?php

namespace SONUser\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator; // Classe que recebe o $data e a $entity e seta
use Zend\Mail\Transport\Smtp as SmtpTransport;
use SONBase\Mail\Mail; // Classe que recebe os dados e prepara o email

/**
 * Esta classe e responsavel por inserir, ativar e alterar o usuario
 */
class User extends AbstractService{
    
    protected $transport;
    protected $view;

    /**
     * Recebe estes parâmetros pelo module.php/getServiceConfig()
     * @param EntityManager $em
     * @param SmtpTransport $transport
     * @param type $view
     */
    function __construct(EntityManager $em, SmtpTransport $transport, $view) {
        parent::__construct($em);        
        $this->entity = "SONUser\Entity\User";
        $this->transport = $transport;
        $this->view = $view;            
    }
    
    /**
     * Insere o usuario e envia um email para ativação da conta
     * @param array $data
     * @return type
     */
    public function insert(array $data){        
        $entity = parent::insert($data);        
        $dataEmail = array('name' => $data['name'], 'activationkey' => $entity->getActivationKey());        
        if($entity){
            $mail = new Mail($this->transport, $this->view, 'add-user');
            $mail->setSubject('Confirmação de cadastro')
                 ->setTo($data['email'])
                 ->setData($dataEmail)
                 ->prepare()
                 ->send();            
            return $entity;            
        }
    }
    
    /**
     * Ativa o usuario comparando o key que foi para seu email e o key que esta no banco de dados
     * @param type $key
     * @return type
     */
    public function activate($key){
        $repo = $this->em->getRepository("SONUser\Entity\User");        
        $user = $repo->findOneByActivationKey($key);        
        if($user && !$user->getActive()){
            $user->setActive(true);
            $this->em->persist($user);
            $this->em->flush();            
            return $user;
        }
    }

    /**
     * Recebe os dados e altera
     * @param array $data
     * @return type
     */
    public function update(array $data) {
        $entity = $this->em->getReference($this->entity, $data['id']);
        if(empty($data["password"])){
            unset($data["password"]);
        }        
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;    
    }
    
}
