<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;
use SONUser\Form\User as FormUser;

class IndexController extends AbstractActionController {

    public function registerAction() {
        $form = new FormUser;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                
                $service = $this->getServiceLocator('SONUser\Service\User');
               // var_dump($service);exit;
                if ($service->insert($request->getPost()->toArray())) {
                    
                    $fm = $this->flashMessenger()->setNamespace('SONUser')->addMessage('Usuario cadastrado com sucesso');
                    return $this->redirect()->toRoute('sonuser-register');
                }
            }
        }
        $messages = $this->flashMessenger()->setNamespace('SONUser')->getMessages();
        return new ViewModel(array('form' => $form, 'messages' => $messages));
    }

}
