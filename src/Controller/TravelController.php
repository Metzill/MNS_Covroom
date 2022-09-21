<?php

namespace App\Controller;

use App\Entity\Travel;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/travel", name="travel_")
 */
class TravelController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $travel = $doctrine
            ->getRepository(Travel::class)
            ->findAll();

        return new JsonResponse($travel);
    }
}
