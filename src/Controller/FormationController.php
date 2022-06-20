<?php

namespace App\Controller;

use App\Entity\Etudiants;
use App\Form\EtudiantType;
use App\Services\CheckNom;
use App\Repository\EtudiantsRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FormationController extends AbstractController
{


    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }


    /**
     * @Route("/formation", name="formation")
     */
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'Formation',
            'nom' => 'khaled',
        ]);
    }





    /**
     * @Route("/addEtudiant", name="addEtudiant")
     */
    public function ajout(SessionInterface $session, EtudiantsRepository $etudRepo, CheckNom $service,EntityManagerInterface $em,Request $request)
    {
        $etudiant = new  Etudiants();
        $form=$this->createForm(EtudiantType::class,$etudiant);
        $form->handleRequest($request);  // Récupérer la request(les données de la requete)

        if($form->isSubmitted() && $form->isValid()){


             //dd( $session->get('idUser'));


            if($service->check_string($etudiant->getNom())<6){
                throw new \Exception('nom doit etre sup a 6');
            } else{

             $nbrNom= $etudRepo->nbrEtudiantNom($etudiant->getNom());
             
            if ($nbrNom<2) {
                
                $em->persist($etudiant);
                $em->flush();
                $this->addFlash('success', 'etudiant '.$etudiant->getNom().'  inserer');

            }else {
                $this->get('session')->getFlashBag()->clear();

                
               $this->addFlash('warning', 'etudiant '.$etudiant->getNom().'  existe');
            }
                // return $this->redirectToRoute('listes');
            }
        }
        return $this->render('etudiant/ajout.html.twig', [
            'formInscription' =>$form->createView(),
        ]);
    }



    /**
     * @Route("CRUD",name="CRUD")
     */
    public function listes_etudiants(Request $request,EtudiantsRepository $repo,PaginatorInterface $paginator){
        // $listes = $repo->findAll();
        // //$listes = $repo->findLatest();

       
        // return $this->render('etudiant/list.html.twig',[
        //     "etudiants"=>$listes
        // ]) ;

        $listessss = $repo->findAll();

        
         
        $listes = $paginator->paginate(
            $listessss, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );
        return $this->render('etudiant/list.html.twig',[
            "etudiants"=>$listes
        ]) ;

    }




    /**
     * @Route("delete_etudiant/{id}",name="delete_etudiant")
     */

    public function delete_etudiant($id,EtudiantsRepository $repo,EntityManagerInterface $em){

        $etudiant = $repo->find($id);

        if ( !$etudiant)
        {
            throw $this->createNotFoundException("L'etudiant d'id " .$id.  " n'existe pas.");

        }

        $em->remove($etudiant);
        $em->flush();
        // $this->addFlash('danger', 'etudiant '.$id.'  a bien étais supprimer');
        return $this->redirectToRoute('CRUD');
    }




    /**
     * @Route("update_etudiant/{id}",name="update_etudiant")
     */
    public function update_etudiant($id,Request $request,EtudiantsRepository $repo,EntityManagerInterface $em){
        $etudiant = $repo->findOneById($id);
        $form = $this->createFormBuilder($etudiant)
            /*    ->add('nom', TextType::class,[	'attr' => ['maxlength' => 20, 	'placeholder'=>'nom'   ]     ]) */
            ->add('nom')
            ->add('prenom')
            ->add('cin')
            ->add('age')
            ->getForm()  ;
        $form->handleRequest($request);  // Récupérer la request(les données de la requete)

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($etudiant);
            $em->flush();
            return $this->redirectToRoute('CRUD');
        }
        return $this->render('etudiant/update.html.twig', [
            'formEtudiant' => $form->createView(),
            'etudiant' => $etudiant,
        ]);
    }



    /**
     * @Route("/upload", name="upload")
     */


    public function upload( Request $request,EtudiantsRepository $repo, EntityManagerInterface $em)
    {

        $form = $this->createFormBuilder()
            ->add('file', FileType::class,[    'label' => 'Brochure (Excel file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes

            ])

            ->getForm();

        $form->handleRequest($request);  // Récupérer la request(les données de la requete)
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            //$file = $request->files->get('file'); // get the file from the sent request

            $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored

            $filePathName = md5(uniqid()) . $file->getClientOriginalName();
            // apply md5 function to generate an unique identifier for the file and concat it with the file extension
            try {
                $file->move($fileFolder, $filePathName);
            } catch (FileException $e) {
                dd($e);
            }
            $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
            $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
            //dd($sheetData);

            $entityManager = $this->getDoctrine()->getManager();
            foreach ($sheetData as $Row)
            {

                $nom = $Row['A']; // store the first_name on each iteration
                $prenom = $Row['B']; // store the last_name on each iteration
                $age = $Row['C']; // store the last_name on each iteration
                $cin = $Row['D']; // store the last_name on each iteration



                $user_existant = $repo->findOneBycin( $cin);;
                // make sure that the user does not already exists in your db
                if (!$user_existant)
                {
                    $student = new Etudiants();
                    $student->setNom($nom);
                    $student->setPrenom($prenom);
                    $student->setCin($cin);
                    $student->setAge($age);

                    $entityManager->persist($student);
                    $entityManager->flush();
                    // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
                }
            }
            return $this->json('users registered', 200);


        }
        return $this->render('excel/upload.html.twig', [
            'formUpload' => $form->createView()
        ]);

    }


}
