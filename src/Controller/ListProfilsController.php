<?php

namespace App\Controller;

use App\Form\ImageType;
use App\Repository\ImageRepository;
 use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
 use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListProfilsController extends AbstractController
{

    

    /**
     * @Route("/list/profils", name="list_profils")
     */
    public function index(Request $request,PaginatorInterface  $paginator,ImageRepository $repo): Response
    {

        $listessss = $repo->findAllArray();
       
 
         for ($i=0; $i <count($listessss) ; $i++) { 
            
                if ($listessss[$i]->getAuteur()==$request->getSession()->get('idUser')) {
                $listessss[$i]->setautBlog(1);
               }else {
                $listessss[$i]->setautBlog(0);
               }
         }
        
         
        $listes = $paginator->paginate(
            $listessss, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
    

        return $this->render('list_profils/index.html.twig', [
            'lists' => $listes,
        ]);
    }
}
