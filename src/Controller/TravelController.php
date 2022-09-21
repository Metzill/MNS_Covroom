<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Travel;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @Route("/new", name="new")
     */
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $travel = new Travel();

        $car = $doctrine->getRepository(Car::class)->find($request->query->get('idCar'));
        $user = $doctrine->getRepository(User::class)->find($request->query->get('idUser'));


        $travel->setIdCar($car);
        $travel->setIdUser($user);
        $travel->setSeatAtTheBegining($request->query->get('seatAtTheBegining'));
        $travel->setStartLatitude($request->query->get('startLatitude'));
        $travel->setStartLongitude($request->query->get('startLongitude'));
        $travel->setEndLatitude($request->query->get('endLatitude'));
        $travel->setEndLongitude($request->query->get('endLongitude'));
        $travel->setStartCity($request->query->get('startCity'));
        $travel->setEndCity($request->query->get('endCity'));
//        temporaire
        $travel->setStartTime($today);
        $travel->setEndTime($today);

        $travel->setCreatedAt($today);
        $travel->setUpdatedAt($today);

        $entityManager->persist(($travel));

        $entityManager->flush();

        return new Response('Saved new user with id '.$travel->getId());
    }
}
