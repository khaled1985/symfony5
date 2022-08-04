<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax", name="app_ajax")
     */
    public function index(CountryRepository $count): Response
    {

        $listes="";
        $countries=$count->findAll();
       
        foreach($countries as $Country){
        
          $listes.=   " <option value=\"".$Country->getId()."\">".$Country->getName()."</option>" ;
        }
     
        return $this->render('ajax/index.html.twig', [
            'listesPays' =>  $listes,
        ]);
    }


    /**
     * @Route("/getCities", name="getCities")
     */
    public function getCities(Request $request,CityRepository $repoCity) 

    {
      $idCountry= $request->request->get('idCountry');
      $listesCity=$repoCity->getCity($idCountry);
      
        $listes="";
         
      foreach($listesCity as $City){
        
        $listes.=   " <option value=\"".$City->getId()."\">".$City->getCity()."</option>" ;
      }
    
        echo $listes;

      $response=new Response();
      $response->listes=$listes;
      
       return $response;


    }
 
}
