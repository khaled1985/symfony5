<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ImageUploadController extends AbstractController
{
    /**
     * @Route("/image", name="image_upload")
     */
    public function index(Request $request,EntityManagerInterface $em,SluggerInterface $slugger): Response
    {
        $img=new Image();
            $form=$this->createForm(ImageType::class,$img);

           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid() ) {
            $blog = $form->getData();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image cannot be saved.');
                }
                $blog->setImage($newFilename);
            }

            $em->persist($blog);
            $em->flush();
            $this->addFlash('success', 'Blog was created!');


           }
        return $this->render('image_upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/edit/{id}",name="updateImage")
     *
     * @ParamConverter("image", class="App:Image")
     *
     * @return Response
     */
    public function editImage(Image $image,Request $request,EntityManagerInterface $em,SluggerInterface $slugger): Response
    {
       
      $image->setImage( $image->getImage());
        
          $img=$image->getImage();
          
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image      = $form->getData();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename  = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                            $this->getParameter('image_directory'),
                            $newFilename
                        );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image cannot be saved.');
                }
                $image->setImage($newFilename);
            }

            $em->persist($image);
            $em->flush();
            $this->addFlash('success', 'Blog was edited!');
        }

        return $this->render('image_upload/edit.html.twig', [
            'form' => $form->createView(),
            'img'=>$img,
        ]);
    }


    
    /**
     * 
     * @Route("delete/{id}",name="deleteImage");
     * 
     * * @param Image                   $image
     */

    public function deleteImage(Image $image,Request $request,EntityManagerInterface $em,SluggerInterface $slugger){

        $em->remove($image);
        $em->flush();

        
        return $this->redirectToRoute('home');
     }



         
    /**
     * 
     * @Route("delete/{id}/{title}",name="deleteImageList");
     * 
     * * @param Image                   $image
     */

    public function deleteImageList(Image $image,Request $request,EntityManagerInterface $em,SluggerInterface $slugger){
        //dd($image);
                $em->remove($image);
                $em->flush();
        
                
                return $this->redirectToRoute('home');
             }

             


     /**
      * @Route("/crudImage",name="crud_image")
      */

      public function crudImage(ImageRepository $repo){

        $listImage=$repo->findAll();
       return $this->render('image_upload/list.html.twig',[
            "lists"=>$listImage,
        ]);
      }

    
}