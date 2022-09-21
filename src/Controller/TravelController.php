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
        $newPostJson = json_decode($request->getContent(), true);

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $travel = new Travel();


        $car = $doctrine->getRepository(Car::class)->find($newPostJson['idCar']);
        $user = $doctrine->getRepository(User::class)->find($newPostJson['idUser']);


        $travel->setIdCar($car);
        $travel->setIdUser($user);
        $travel->setSeatAtTheBegining($newPostJson['seatAtTheBegining']);
        $travel->setStartLatitude($newPostJson['startLatitude']);
        $travel->setStartLongitude($newPostJson['startLongitude']);
        $travel->setEndLatitude($newPostJson['endLatitude']);
        $travel->setEndLongitude($newPostJson['endLongitude']);
        $travel->setStartCity($newPostJson['startCity']);
        $travel->setEndCity($newPostJson['endCity']);
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
