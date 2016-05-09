<?php

namespace MongoDBBundle\Entity;

/**
 * Tweet
 *
 */
class Tweet
{
    /**
     * @var integer
     *
     *
     */
    private $id;

    /**
     * @var string
     *
     *
     */
    private $fechaCreacion;

    /**
     * @var string
     *
     * 
     */
    private $tweetContent;

    /**
     * @var string
     *
     * 
     */
    private $cantidadRetweet;

    /**
     * @var string
     *
     * 
     */
    private $cantidadLikes;

    /**
     * @var string
     *
     *
     */
    private $idioma;

    /**
     * @var string
     *
     * 
     */
    private $replyUsername;

    /**
     * @var string
     *
     * 
     */
    private $image;

    /**
     * @var string
     *
     *
     */
    private $userName;

    /**
     * @var string
     *
     * 
     */
    private $userScreenName;

    /**
     * @var string
     *
     * 
     */
    private $userFriendsCount;

    /**
     * @var string
     *
     *
     */
    private $userFollowersCount;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaCreacion
     *
     * @param string $fechaCreacion
     *
     * @return Tweet
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return string
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set tweetContent
     *
     * @param string $tweetContent
     *
     * @return Tweet
     */
    public function setTweetContent($tweetContent)
    {
        $this->tweetContent = $tweetContent;

        return $this;
    }

    /**
     * Get tweetContent
     *
     * @return string
     */
    public function getTweetContent()
    {
        return $this->tweetContent;
    }

    /**
     * Set cantidadRetweet
     *
     * @param string $cantidadRetweet
     *
     * @return Tweet
     */
    public function setCantidadRetweet($cantidadRetweet)
    {
        $this->cantidadRetweet = $cantidadRetweet;

        return $this;
    }

    /**
     * Get cantidadRetweet
     *
     * @return string
     */
    public function getCantidadRetweet()
    {
        return $this->cantidadRetweet;
    }

    /**
     * Set cantidadLikes
     *
     * @param string $cantidadLikes
     *
     * @return Tweet
     */
    public function setCantidadLikes($cantidadLikes)
    {
        $this->cantidadLikes = $cantidadLikes;

        return $this;
    }

    /**
     * Get cantidadLikes
     *
     * @return string
     */
    public function getCantidadLikes()
    {
        return $this->cantidadLikes;
    }

    /**
     * Set idioma
     *
     * @param string $idioma
     *
     * @return Tweet
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * Get idioma
     *
     * @return string
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set replyUsername
     *
     * @param string $replyUsername
     *
     * @return Tweet
     */
    public function setReplyUsername($replyUsername)
    {
        $this->replyUsername = $replyUsername;

        return $this;
    }

    /**
     * Get replyUsername
     *
     * @return string
     */
    public function getReplyUsername()
    {
        return $this->replyUsername;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Tweet
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return Tweet
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userScreenName
     *
     * @param string $userScreenName
     *
     * @return Tweet
     */
    public function setUserScreenName($userScreenName)
    {
        $this->userScreenName = $userScreenName;

        return $this;
    }

    /**
     * Get userScreenName
     *
     * @return string
     */
    public function getUserScreenName()
    {
        return $this->userScreenName;
    }

    /**
     * Set userFriendsCount
     *
     * @param string $userFriendsCount
     *
     * @return Tweet
     */
    public function setUserFriendsCount($userFriendsCount)
    {
        $this->userFriendsCount = $userFriendsCount;

        return $this;
    }

    /**
     * Get userFriendsCount
     *
     * @return string
     */
    public function getUserFriendsCount()
    {
        return $this->userFriendsCount;
    }

    /**
     * Set userFollowersCount
     *
     * @param string $userFollowersCount
     *
     * @return Tweet
     */
    public function setUserFollowersCount($userFollowersCount)
    {
        $this->userFollowersCount = $userFollowersCount;

        return $this;
    }

    /**
     * Get userFollowersCount
     *
     * @return string
     */
    public function getUserFollowersCount()
    {
        return $this->userFollowersCount;
    }
}

