<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Seat;
use App\Entity\Travel;
use App\Entity\User;
use DateInterval;
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
        $travels = $doctrine
            ->getRepository(Travel::class)
            ->findAll();

        $travel_data = [];


        foreach ($travels as $travel) {
            $driver = $doctrine->getRepository(User::class)->findBy(['id'=>$travel->getIdUser()->getId()]);
            $travel_data[] = [
                'id' => $travel->getId(),
                'start_city' => $travel->getStartCity(),
                'end_city' => $travel->getEndCity(),
                'user' => ['name' =>$driver[0]->getName(),'firstname' => $driver[0]->getFirstName()],
                'startAt' => $travel->getStartTime(),
                'endAt' => $travel->getEndTime(),
                'travelTime' => '1h30',
            ];
        }

        return $this->json($travel_data);
    }

    /**
     * @Route("/retrieve/{id}", name="retrieve<")
     */
    public function retrieve(ManagerRegistry $doctrine, $id): Response
    {
        $travel = $doctrine
            ->getRepository(Travel::class)
            ->find($id);

        $driver = $doctrine->getRepository(User::class)->findBy(['id'=>$travel->getIdUser()->getId()]);

        $seats = $doctrine->getRepository(Seat::class)->findBy(['IdTravel'=>$travel->getId()]);

        $car = $doctrine->getRepository(Car::class)->findBy(['id'=>$travel->getIdCar()->getId()]);

        $available =0;
        foreach ($seats as $seat) {
            if (!$seat->isStatus()){
                $available++;
            }
        }

        $travel_data =  [
            'id' => $travel->getId(),
            'start_city' => $travel->getStartCity(),
            'end_city' => $travel->getEndCity(),
            'user' => ['name' =>$driver[0]->getName(),'firstname' => $driver[0]->getFirstName()],
            'startAt' => $travel->getStartTime(),
            'endAt' => $travel->getEndTime(),
            'travelTime' => '1h30',
            'seat'=> [
                'available'=>$available,
                'max'=>count($seats),
            ],
            'car'=> [
                'model'=>$car[0]->getModel(),
                'color'=>$car[0]->getColor(),
                'numberplate'=>$car[0]->getNumberplate(),
            ]
        ];

        return $this->json($travel_data);
    }

    /**
     * @Route("/new", name="new")
     * @throws \Exception
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
        $start_at = new DateTime();
        $start_at->setTimezone(new DateTimeZone("UTC"));
        $dateHours = explode('T',$newPostJson['startDay']);
        $date = explode('-',$dateHours[0]);
        $hours = explode(':',$dateHours[1]);

        date_date_set($start_at,$date[0],$date[1],$date[2]);
        date_time_set($start_at,$hours[0],$hours[1]);
        $travel->setStartTime($start_at);

        $travelTime = explode('h',$newPostJson['travelTime']);
        $string = 'PT' . $travelTime[0] . 'H' . $travelTime[1] . 'M';

        $test = new DateTime();
        $test->setTimezone(new DateTimeZone("UTC"));
        $dateHours = explode('T',$newPostJson['startDay']);
        $date = explode('-',$dateHours[0]);
        $hours = explode(':',$dateHours[1]);

        date_date_set($test,$date[0],$date[1],$date[2]);
        date_time_set($test,$hours[0],$hours[1]);

        $end_at = date_add($test, new DateInterval($string));

        $travel->setEndTime($end_at);

        $travel->setCreatedAt($today);
        $travel->setUpdatedAt($today);

        $entityManager->persist(($travel));

        $entityManager->flush();

        //créer les seats vide lié à ce trajet
        $seatMax = $newPostJson['seatAtTheBegining'];

        for ($i = 0; $i<$seatMax; $i++) {
            $seat = new Seat ();
            $seat->setStatus(0);
            $seat->setCreatedAt($today);
            $seat->setIdTravel($travel);

            $entityManager->persist(($seat));

            $entityManager->flush();
        }

        return new Response('Saved new travel with id '.$travel->getId());
    }

    /**
     * @Route("/last/{numberOfLast}", name="last")
     */
    public function last(ManagerRegistry $doctrine, $numberOfLast): Response
    {
        $travels = $doctrine
            ->getRepository(Travel::class)
            ->findAll();

        $travels = array_reverse($travels);

        $tmp = [];

        $max = min($numberOfLast,count($travels));

        for ($i=0; $i<$max; $i++) {
            array_push($tmp,$travels[$i]);
        }

        $travels = $tmp;

        $travel_data = [];

        foreach ($travels as $travel) {
            $driver = $doctrine->getRepository(User::class)->findBy(['id'=>$travel->getIdUser()->getId()]);
            $travel_data[] = [
                'id' => $travel->getId(),
                'start_city' => $travel->getStartCity(),
                'end_city' => $travel->getEndCity(),
                'user' => ['name' =>$driver[0]->getName(),'firstname' => $driver[0]->getFirstName()],
                'startAt' => $travel->getStartTime(),
                'endAt' => $travel->getEndTime(),
                'travelTime' => '1h30',
            ];
        }

        return $this->json($travel_data);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(ManagerRegistry $doctrine): Response
    {
        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $travels = $doctrine
            ->getRepository(Travel::class)
            ->findAll();

        $tmp = [];

        foreach ($travels as $travel) {
            $isFuture = false;
            $seatFree = false;
            $seats = $doctrine->getRepository(Seat::class)->findBy(['IdTravel'=>$travel->getId()]);

            if ($today < $travel->getStartTime()) {
                $isFuture = true;
            }
            foreach($seats as $seat) {
                if ($seat->isStatus() === false){
                    $seatFree = true;
                }
            }
            if ($isFuture && $seatFree) {
                array_push($tmp,$travel);
            }
        }

        $travels = $tmp;

        $travel_data = [];


        foreach ($travels as $travel) {
            $driver = $doctrine->getRepository(User::class)->findBy(['id'=>$travel->getIdUser()->getId()]);
            $travel_data[] = [
                'id' => $travel->getId(),
                'start_city' => $travel->getStartCity(),
                'end_city' => $travel->getEndCity(),
                'user' => ['name' =>$driver[0]->getName(),'firstname' => $driver[0]->getFirstName()],
                'startAt' => $travel->getStartTime(),
                'endAt' => $travel->getEndTime(),
                'travelTime' => '1h30',
            ];
        }

        return $this->json($travel_data);
    }
}
