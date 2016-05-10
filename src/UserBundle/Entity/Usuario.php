<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * 
 */
class Usuario extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $twitterToken;

    protected $twitter_id;

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
