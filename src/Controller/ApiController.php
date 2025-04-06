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
    private $validator;
    private $serializer;
    private $entityManager;

    public function __construct(
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ) {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/login_check", name="api_login")
     */
    public function apiLogin(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json([
            'email' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }

    /**
     * @Route("/listes", name="liste", methods={"GET"})
     */
    public function listes(EtudiantsRepository $repo): JsonResponse
    {
        return $this->json(
            $repo->findAll(),
            Response::HTTP_OK,
            [],
            ['groups' => 'post:read']
        );
    }

    /**
     * @Route("/etudiants/supprimer/{id}", name="supprimer", methods={"DELETE"})
     */
    public function removeEtudiant(Etudiants $etudiant): JsonResponse
    {
        $nom = $etudiant->getNom();
        $this->entityManager->remove($etudiant);
        $this->entityManager->flush();
        
        return $this->json($nom, Response::HTTP_OK);
    }

    /**
     * @Route("/etudiantsss/ajout", name="ajoutapi", methods={"POST"})
     */
    public function ajoutEtudiant(Request $request): JsonResponse
    {
        try {
            $etudiant = $this->serializer->deserialize(
                $request->getContent(),
                Etudiants::class,
                'json'
            );

            $errors = $this->validator->validate($etudiant);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($etudiant);
            $this->entityManager->flush();

            return $this->json($etudiant, Response::HTTP_CREATED);

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/etudiantsss/editer/{id}", name="editer", methods={"PUT"})
     */
    public function editerEtudiant(
        Etudiants $etudiant,
        Request $request
    ): JsonResponse {
        try {
            $etudiantData = $this->serializer->deserialize(
                $request->getContent(),
                Etudiants::class,
                'json'
            );

            $errors = $this->validator->validate($etudiantData);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $etudiant->setAge($etudiantData->getAge())
                    ->setCin($etudiantData->getCin())
                    ->setNom($etudiantData->getNom())
                    ->setPrenom($etudiantData->getPrenom());

            $this->entityManager->flush();

            return $this->json($etudiant, Response::HTTP_OK);

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}