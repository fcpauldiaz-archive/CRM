<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;

class UserManager extends BaseUserManager
{
    protected $objectManager;
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class, EntityManager $em)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer);

        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteUser(UserInterface $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function reloadUser(UserInterface $user)
    {
        $this->objectManager->refresh($user);
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);
        $usuario = $user;
        $this->objectManager->persist($user);

        /*$this->em->getRepository('UserBundle:Usuario')->findById(1);*/
         $sql = " 
            INSERT INTO usuario VALUES (
                8, 
                :username, 
                :usernameCanonical,
                :email, 
                :emailCanonical, 
                TRUE,
                :salt, 
                :password, 
                 null,
                FALSE,
                FALSE,
                null,
                null,
                null, 
                :roles, 
                FALSE,
                null
            )
            ";
        //$stmt = $this->em->getConnection()->prepare($sql);
      
        /*$stmt->bindValue("username", $usuario->getUsername());
        $stmt->bindValue("usernameCanonical", $usuario->getUsernameCanonical());
        $stmt->bindValue("email", $usuario->getEmail());
        $stmt->bindValue("emailCanonical", $usuario->getEmailCanonical());
        //$stmt->bindValue("enabled", TRUE);
        $stmt->bindValue("salt", $usuario->getSalt());
        $stmt->bindValue("password", $usuario->getPassword());
       // $stmt->bindValue("lastLogin", $usuario->getLastLogin());*/
      
        //$stmt->bindValue("expiresAt", null);
        //$stmt->bindValue("confirmationToken", $usuario->getConfirmationToken());
        //$stmt->bindValue("passwordRequestedAt", $usuario->getPasswordRequestedAt());
        //$stmt->bindValue("roles", "a:0:{}");
        //$stmt->bindValue("credentialsExpired", FALSE);
        //$stmt->bindValue("credentialsExpireAt", null);
        /*$params = [];
        $params["username"] = $usuario->getUsername();
        $params["usernameCanonical"] = $usuario->getUsernameCanonical();
        $params["email"] = $usuario->getEmail();
        $params["emailCanonical"] = $usuario->getEmailCanonical();
        $params["salt"] = $usuario->getSalt();
        $params["password"]=$usuario->getPassword();
        $params["roles"] = "a:0:{}";
        $this->em->getConnection()->executeUpdate($sql, $params);*/
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}
