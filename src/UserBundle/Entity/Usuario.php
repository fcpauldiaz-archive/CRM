<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 */
class Usuario extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     *  @ORM\Column(name="twitter_token", type="string", length=255, nullable=true) 
     */
    protected $twitterToken;

     /** 
      * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
      */
    protected $twitter_id;

    /**
     * @ORM\Column(name="twitter_secret_token", type="string", length=255, nullable=true)
     */
    protected $twitterSecretToken;

    public function __construct()
    {
        parent::__construct();
        
    }

    public function setTokenSecret($token) 
    {
        $this->twitterSecretToken = $token;
    }

    public function getTokenScret()
    {
        return $this->twitterSecretToken;
    }

    /**
     * Set twitterToken
     *
     * @param string $twitterToken
     *
     * @return Usuario
     */
    public function setTwitterAccessToken($twitterToken)
    {
        $this->twitterToken = $twitterToken;

        return $this;
    }
     /**
     * Set twitterToken
     *
     * @param string $twitterToken
     *
     * @return Usuario
     */
    public function setTwitteroken($twitterToken)
    {
        $this->twitterToken = $twitterToken;

        return $this;
    }

    /**
     * Get twitterToken
     *
     * @return string
     */
    public function getTwitterToken()
    {
        return $this->twitterToken;
    }

     /**
     * Get twitterToken
     *
     * @return string
     */
    public function getTwitterAccessToken()
    {
        return $this->twitterToken;
    }

    /**
     * Set twitterId
     *
     * @param string $twitterId
     *
     * @return Usuario
     */
    public function setTwitterId($twitterId)
    {
        $this->twitter_id = $twitterId;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }
}
