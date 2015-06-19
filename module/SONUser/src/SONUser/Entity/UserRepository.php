<?php

namespace SONUser\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{    
    
    /**
     * Busca um registro com este email,encripta a senha para verifica se e igual
     */
    public function findByEmailAndPassword($email, $password){
        
        $user = $this->findOneByEmail($email);
        
        if($user){
            $hashSenha = $user->encryptPassword($password);
            
            if($hashSenha == $user->getPassword()){
                return $user;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
}
