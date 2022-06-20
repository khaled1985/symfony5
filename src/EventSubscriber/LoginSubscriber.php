<?php

namespace App\EventSubscriber;

use App\Entity\SauvAuhentification;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{

    public function __construct(RequestStack $requestStack,EntityManagerInterface $em )
    {
        
        $this->session = $requestStack;
        $this->em=$em;
      
        
        
     }
    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {

       if($event->getauthenticationToken()->getUser()!= "anon."){ 

           $idUser=$event->getauthenticationToken()->getUser()->getId();          
           $user=$event->getauthenticationToken()->getUser();          
           $session = $this->session->getSession(); 
           

            $sauv=new SauvAuhentification;            
            $sauv->setDate(new DateTime());
            $sauv->setIdUser($user);
            $this->em->persist($sauv);
            $this->em->flush();
 

           $session->set('idUser', $idUser);
       }
    }

    public static function getSubscribedEvents()
    {
        return [
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
        ];
    }
}
