<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Direction;
use App\Entity\Etudiants;
use App\Form\FormRechercheType;
use App\Repository\EtudiantsRepository;
use Doctrine\ORM\EntityManagerInterface;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{


     /**
     * @Route("/etudiantsQuery", name="etudiantsQuery")
     */
    public function index(): Response
    {
       return new JsonResponse(" [
            { id: 1, nom: 'Displayed Text 1' },
            { id: 2, nom: 'Displayed Text 2' }
          ]");
    }



    /**
     * @Route("/recherche", name="app_recherche")
     */
    public function recherche(Request $request): Response
    {
        
        $form = $this->createFormBuilder()
      
        ->add('nom', EntityType::class, [
            'class' => Etudiants::class,
            
            // uses the User.username property as the visible option string
            'choice_label' => 'prenom',
            'label' => 'Recherche par prenom',
            
            'query_builder' => function (EtudiantsRepository  $er) {
                return $er->createQueryBuilder('s')
                ->where('s.id>20')
                    ->orderBy('s.prenom', 'DESC');
            },
        
            // 'object_manager' => $objectManager, // inject a custom object / entity manager 
        ])
        ->add('Recherche',SubmitType::class)
            ->getForm()  ;
        $form->handleRequest($request);  // Récupérer la request(les données de la requete)

        if($form->isSubmitted() && $form->isValid()){
            dd($form->getData());
        }
        return $this->render('recherche/index.html.twig', [
            'form' => $form->createView(),
           
        ]);
    }




    
    /**
     * @Route("form_recherche",name="form_recherche")
     */
    public function form_recherche(Request $request, EntityManagerInterface $em): Response
    {
        $countr=new Direction;
        $form = $this->createForm(FormRechercheType::class, $countr);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $this->addFlash('success', 'Thanks for your message. We\'ll get back to you shortly.');
            return $this->redirectToRoute('form_recherche');
        }

        return $this->render('recherche/home.html.twig',['form'=>$form->createView()]);
         
    }

}
