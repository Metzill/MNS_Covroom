<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\Seat;
use App\Entity\Travel;
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
            $emptySeats[$i]->setIdBooking($booking);
            $entityManager->persist(($emptySeats[$i]));
            $entityManager->flush();
        }
        return $this->json([
            'code' => '1',
            'id' => $booking->getId()
        ]);
    }

    /**
     * @Route("/retrieve/user/{id_user}", name="retrieveByUser")
     */
    public function retrieveByUser(ManagerRegistry $doctrine, Request $request, $id_user): Response
    {
        $bookings = $doctrine
            ->getRepository(Booking::class)
            ->findBy(['IdUser'=>$id_user]);

        $travel_array = [];

        foreach ($bookings as $booking) {
            $seats = $doctrine->getRepository(Seat::class)->findBy(['IdBooking'=>$booking->getId()]);
            $travel = $doctrine->getRepository(Travel::class)->findBy(['id'=>$seats[0]->getIdTravel()->getId()]);
            array_push($travel_array, $travel[0]->getId());
        }

        $dataToReturn = [];

        foreach ($travel_array as $travelId) {
            $travel = $doctrine->getRepository(Travel::class)->findBy(['id'=>$travelId]);
            $travel = $travel[0];
            $driver = $doctrine->getRepository(User::class)->findBy(['id'=>$travel->getIdUser()->getId()]);
            $car = $doctrine->getRepository(Car::class)->findBy(['id'=>$travel->getIdCar()->getId()]);

            $booking_data =  [
                'id' => $travel->getId(),
                'start_city' => $travel->getStartCity(),
                'end_city' => $travel->getEndCity(),
                'user' => ['name' =>$driver[0]->getName(),'firstname' => $driver[0]->getFirstName(), 'id' => $driver[0]->getId()],
                'startAt' => $travel->getStartTime(),
                'endAt' => $travel->getEndTime(),
                'travelTime' => '1h30',
                'seat'=> count($seats),
                'car'=> [
                    'model'=>$car[0]->getModel(),
                    'color'=>$car[0]->getColor(),
                    'numberplate'=>$car[0]->getNumberplate(),
                ]
            ];
            array_push($dataToReturn, $booking_data);
        }
        return $this->json($dataToReturn);
    }

    /**
     * @Route("/retrieve/{id}", name="retrieve")
     */
    public function retrieve(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $booking = $doctrine
            ->getRepository(Booking::class)
            ->find($id);
        $seats = $doctrine->getRepository(Seat::class)->findBy(['IdBooking'=>$booking->getId()]);
        $travel = $doctrine->getRepository(Travel::class)->findBy(['id'=>$seats[0]->getIdTravel()->getId()]);
        $travel = $travel[0];
        $driver = $doctrine->getRepository(User::class)->findBy(['id'=>$travel->getIdUser()->getId()]);
        $car = $doctrine->getRepository(Car::class)->findBy(['id'=>$travel->getIdCar()->getId()]);

        $booking_data =  [
            'id' => $travel->getId(),
            'start_city' => $travel->getStartCity(),
            'end_city' => $travel->getEndCity(),
            'user' => ['name' =>$driver[0]->getName(),'firstname' => $driver[0]->getFirstName(), 'id' => $driver[0]->getId()],
            'startAt' => $travel->getStartTime(),
            'endAt' => $travel->getEndTime(),
            'travelTime' => '1h30',
            'seat'=> count($seats),
            'car'=> [
                'model'=>$car[0]->getModel(),
                'color'=>$car[0]->getColor(),
                'numberplate'=>$car[0]->getNumberplate(),
            ]
        ];

        return $this->json($booking_data);
    }
}
