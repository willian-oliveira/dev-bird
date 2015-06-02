<?php

namespace SONUser\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;

/**
 * Essa classe contém um CRUD básico
 */
class AbstractService {

    /**
     * @var EntityManager
     */
    protected $em;
    protected $entity;

    /**
     * Recebe por parâmetro a EntityManager
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Recebe um array com os dados e insere
     * @param array $data
     * @return \SONUser\Service\entity
     */
    public function insert(array $data) {
        $entity = new $this->entity($data);
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

    /**
     * Recebe um array com os dados e altera
     * @param array $data
     * @return type
     */
    public function update(array $data) {
        $entity = $this->em->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

    /**
     * Recebe um id e deleta o registro
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $entity = $this->em->getReference($this->entity, $id);
        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
            return $id;
        }
    }

}
