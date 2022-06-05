<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{



    /**
     * @Route("/login", name="login")
     */
    public function login(){

        return $this->render('security/home.html.twig');
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }


    /**
     * @Route("/security", name="security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }




    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    {
        $user=new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);  // Récupérer la request(les données de la requete)
        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            /* $roles="Role_User";
               $user->setRoles($roles);*/
            $manager->persist($user);      $manager->flush();
            unset($user);         unset($form);
            $user = new User();
            $form=$this->createForm(UserType::class,$user);
            // $this->addFlash('succes', 'Your changes were saved!' );
          //  return  $this->redirectToRoute('login');
        }
        return $this->render('security/index.html.twig', [
            'formInscription' =>$form->createView(),
        ]);
    }



}
