<?php

namespace SONUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Math\Rand;
use Zend\Crypt\Key\Derivation\Pbkdf2;
use Zend\Stdlib\Hydrator;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="SONUser\Entity\UserRepository")
 */
class User {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_key", type="string", length=255, nullable=false)
     */
    private $activationKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    function __construct(array $options = array()) {
        (new Hydrator\ClassMethods)->hydrate($options, $this);
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");
        $this->salt = base64_encode(Rand::getBytes(8, true));
        $this->activationKey = md5($this->email . $this->salt);
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getSalt() {
        return $this->salt;
    }

    function getActive() {
        return $this->active;
    }

    function getActivationKey() {
        return $this->activationKey;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function getCreatedAt() {
        return $this->createdAt;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    function setPassword($password) {
        $this->password = $this->encryptPassword($password);
        return $this;
    }

    public function encryptPassword($password) {
        return base64_encode(Pbkdf2::calc('sha256', $password, $this->salt, 10000, strlen($password * 2)));
    }

    function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    function setActive($active) {
        $this->active = $active;
        return $this;
    }

    function setActivationKey($activationKey) {
        $this->activationKey = $activationKey;
        return $this;
    }

    /**
     * Para setar sozinho e tem um outro parametro na classe para isso
     * @ORM\prePersist
     */
    function setUpdatedAt() {
        $this->updatedAt = new\DateTime('now');
    }

    function setCreatedAt() {
        $this->createdAt = new\DateTime('now');
    }

}
