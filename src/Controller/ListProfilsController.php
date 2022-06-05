<?php

namespace App\Controller;

use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListProfilsController extends AbstractController
{
    /**
     * @Route("/list/profils", name="list_profils")
     */
    public function index(ImageRepository $repo): Response
    {

      

        return $this->render('list_profils/index.html.twig', [
            'lists' => $repo->findAll(),
        ]);
    }
}
