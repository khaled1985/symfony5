<?php

namespace App\Controller;

use App\Repository\EtudiantsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartController extends AbstractController
{
    /**
     * @Route("/chart", name="chart")
     */
    public function index(ChartBuilderInterface $chartBuilder,EtudiantsRepository $repo,EntityManagerInterface $em): Response
    {

        $labels = [];
        $datasets = [];
       // $repo = $repo->findAll();

        $repo = $repo->findByExampleField();

        foreach($repo as $data){
            $labels[] = $data["nom"];
            $datasets[] = $data["age"];
        }

        return $this->render('chart/index.html.twig', [

            'labels' => $labels,

            'datasets' => $datasets,



        ]);
    }
}
