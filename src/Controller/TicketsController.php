<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Form\TicketsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketsController extends AbstractController
{
    /**
     * @Route("/tickets", name="tickets")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $ticket = new Tickets;

        $form = $this->createForm(TicketsType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->get('city')->getData()!=null  ) {
         
            $em->persist($ticket);
            $em->flush();
        }

        return $this->renderForm('tickets/index.html.twig', compact('form'));
    }
}
