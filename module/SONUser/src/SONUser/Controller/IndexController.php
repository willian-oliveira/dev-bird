<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;
use SONUser\Form\User as FormUser;

/**
 * Classe que controla o que sera feito com o usuario
 */
class IndexController extends AbstractActionController {

    /**
     * Insere um novo usuario
     * @return ViewModel
     */
    public function registerAction() {
        $form = new FormUser;        
        $request = $this->getRequest();        
        if ($request->isPost()) {            
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get('SONUser\Service\User');
                if ($service->insert($request->getPost()->toArray())) {
                    $fm = $this->flashMessenger()->setNamespace('SONUser')->addMessage('Usuario cadastrado com sucesso');
                    return $this->redirect()->toRoute('sonuser-register');
                }
            }
        }
        $messages = $this->flashMessenger()->setNamespace('SONUser')->getMessages();
        return new ViewModel(array('form' => $form, 'messages' => $messages));
    }
    
    /**
     * Ativa o usuario
     * @return ViewModel
     */
    public function activateAction(){
        $activationKey = $this->params()->fromRoute('key');
        $userService = $this->getServiceLocator()->get("SONUser\Service\User");
        $result = $userService->activate($activationKey);        
        if($result){
            return new ViewModel(array("user" => $result));
        }else{
            return new ViewModel();
        }
    }

}
