<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

use SONUser\Form\Login as LoginForm;

class AuthController extends AbstractActionController
{

    public function indexAction()
    {
        $form = new LoginForm;
        $error = false;
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $data = $request->getPost()->toArray();
                // Criando Storage para gravar sessão da authentication
                $sessionStorage = new SessionStorage("SONUser");
                
                $auth = new AuthenticationService;
                // Definindo a SessionStorage para a auth
                $auth->setStorage($sessionStorage);
                
                $authAdapter = $this->getServiceLocator()->get("SONUser\Auth\Adapter");
                $authAdapter->setUsername($data['email']);
                $authAdapter->setPassword($data['password']);
                
                $result = $auth->authenticate($authAdapter);
                
                if($result->isValid())
                {
                    /*
                    $user = $auth->getIdentity();
                    $user = $user['user'];//do metodo authentication da entity
                    $sessionStorage->write($user, null);
                    */
                    $sessionStorage->write($auth->getIdentity()['user'], null);
                    return $this->redirect()->toRoute('sonuser-admin/default', array('controller' => 'users'));
                }else
                {
                    $error = true;
                }
            }
        }
        return new ViewModel(array('form' => $form, 'error' => $error));
    }
    
    
}
