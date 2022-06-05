<?php

namespace App\Controller;

use App\Entity\Etudiants;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatatableController extends AbstractController
{
    /**
     * @Route("/datatable", name="datatable")
     */
        public function showAction(Request $request, DataTableFactory $dataTableFactory)
    {
       $table = $dataTableFactory->create()
        ->add('nom', TextColumn::class)
            ->add('prenom', TextColumn::class)
            ->add('age', TextColumn::class)
            ->add('cin', TextColumn::class)

            ->createAdapter(ORMAdapter::class, [
                'entity' => Etudiants::class,
            ])
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable/index.html.twig', ['datatable' => $table]);

    }



}