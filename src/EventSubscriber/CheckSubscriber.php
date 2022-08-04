<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckSubscriber implements EventSubscriberInterface
{

    public function __construct(RequestStack $requestStack,EntityManagerInterface $em )
    {        
        $this->session = $requestStack;
        $this->em=$em;
            
        
     }

    public function onKernelController(ControllerEvent $event)
    {
      
        $session = $this->session->getSession(); 
        if($session->get("idUser")!=1 && $session->get("idUser")!=null){
          
           dd("not 1 id");
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
