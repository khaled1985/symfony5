<?php

namespace App\Controller;

use App\Entity\Etudiants;
use App\Repository\EtudiantsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


/**
 * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{
    
    /**
     * @Route(name="api_login", path="/api/login_check")
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();
        dd($user);
         return new JsonResponse([
            'email' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }


    /**
     * @Route("/listes", name="liste" ,methods={"GET"})
     */
    public function listes(EtudiantsRepository $repo)
    {

        //$etudiants=$repo->findAll();

      //  $serializedEntity = $this->container->get('serializer')->serialize($etudiants, 'json');

       // return new Response($serializedEntity);
//return $this->json($repo->findAll(),200,[]);
  return $this->json($repo->findAll(),200,[],['groups'=>'post:read']);  //@Groups('post:read');
    }


    /**
     * @Route("/etudiants/supprimer/{id}", name="supprimer" ,methods={"DELETE"})
     */
    public function removeEtudiant(Etudiants $etudiant,EntityManagerInterface $em): Response
    {     $em->remove($etudiant);       $em->flush();   
            //return new Response("OK");   
            return $this->json($etudiant->getNom(),201);
        
        }

    /**
     * @Route("/etudiantsss/ajout", name="ajoutapi" ,methods={"POST"})
     */
    public function ajoutEtudiant(ValidatorInterface $validator, SerializerInterface $serialize, Request $request,EntityManagerInterface $em)
    {

        $jsonRecu=$request->getContent();

        try {
            $etudiant=$serialize->deserialize($jsonRecu,Etudiants::class,'json');
           // $donnees = json_decode($request->getContent());
           

           $errors=$validator->validate($etudiant);

        if (count($errors)>0) {
           return $this->json($errors,400);
        }
            $em->persist($etudiant);
            $em->flush();
           // return new Response("OK");
           return $this->json($etudiant,201,[]);
             
        } catch (NotEncodableValueException $e) {
            return $this->json(["status"=>400,"message"=>$e->getMessage()]);
        }
    }




    /**
     * @Route("/etudiantsss/editer/{id}", name="editer" ,methods={"PUT"})
     */
    public function editerEtudiant(Etudiants $etudiant,ValidatorInterface $validator, SerializerInterface $serialize, Request $request,EntityManagerInterface $em)
    {


       
        $jsonRecu=$request->getContent();
 
        try {


          $etudiant1=$serialize->deserialize($jsonRecu,Etudiants::class,'json');
            // $donnees = json_decode($request->getContent());
       
           if (!$etudiant){

            $etudiant=new Etudiants();
            $code=201;
        }
           $errors=$validator->validate($etudiant);

        if (count($errors)>0) {
           return $this->json($errors,400);
        }
        //$etudiant->setCreatedAt(new \DateTime());
        $etudiant->setAge($etudiant1->getAge());
        $etudiant->setCin($etudiant1->getCin());
        $etudiant->setNom($etudiant1->getNom());
        $etudiant->setPrenom($etudiant1->getPrenom());
        $em->persist($etudiant);
            $em->flush();
           // return new Response("OK");
           return $this->json($etudiant,201,[]);
             
        } catch (NotEncodableValueException $e) {
            return $this->json(["status"=>400,"message"=>$e->getMessage()]);
        }
     

        
  

    }

}