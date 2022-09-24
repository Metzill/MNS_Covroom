<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Seat;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/booking", name="booking_")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BookingController.php',
        ]);
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

        $user = $doctrine->getRepository(User::class)->find($newPostJson['idUser']);

        $booking = new Booking();

        $booking->setStatus('valid');
        $booking->setCreatedAt($today);
        $booking->setIdUser($user);

        $entityManager->persist(($booking));

        $entityManager->flush();

        $seatBooked = $newPostJson['seat'];

        $seats = $doctrine->getRepository(Seat::class)->findBy(['IdTravel'=>$newPostJson['idTravel']]);

        $emptySeats = [];

        foreach ($seats as $seat) {
            if (!$seat->isStatus()){
                array_push($emptySeats,$seat);
            }
        }

        for ($i = 0; $i<$seatBooked; $i++) {
            $emptySeats[$i]->setStatus(true);
            $entityManager->persist(($emptySeats[$i]));
            $entityManager->flush();
        }

        return new Response('Saved new travel with id '.$booking->getId());

    }
}
