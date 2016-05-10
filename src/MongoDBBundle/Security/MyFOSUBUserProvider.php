<?php

namespace MongoDBBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Endroid\Twitter\Twitter;

class MyFOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        //custom setter.
        $user->setTokenSecret($response->getTokenSecret());
        $this->userManager->updateUser($user);
        //obtener credenciales de twitter
        $twitter = new Twitter(
            "ADcfgE61LTgs6YU524t9yrU29", 
            "Z7oggnEwWq4mdOj0oapaH9rteMzURlZFb61IkxEe024tjQrMFU", 
            $user->getTwitterToken(), 
            $user->getTokenScret());
        // obtener tweets del usuario
        // Twitter api retorna máximo 199
        $tweets = $twitter->getTimeline(array(
            'count' => 200
        ));

        //conectar con mongo
        $client = new \MongoDB\Client("mongodb://localhost:27017");

        $collection = $client->crm->tweets;
        foreach($tweets as &$tweet) {
            $date = new \DateTime($tweet->created_at);
            //$date = $date->format(\DateTime::ISO8601);
            dump($date);
            $time = $date->getTimestamp();
            //$time = strval($time) + "000";
            $time = $time."000";
             
            $utcdatetime = new \MongoDB\BSON\UTCDateTime($time);
           
           
            $tweet->created_at = $utcdatetime;
           
        }
        
        //guardar los tweets
        $result = $collection->insertMany($tweets);
        
        
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($username);
            $user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }
}
