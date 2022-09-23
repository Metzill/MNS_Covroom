<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/car", name="car_")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CarController.php',
        ]);
    }

    /**
     * @Route("/user/{user_id}", name="findOne")
     */
    public function byUser(ManagerRegistry $doctrine, $user_id):Response
    {
        $cars = $doctrine
            ->getRepository(Car::class)
            ->findBy(['IdUser'=>$user_id]);
        $dataToReturn = [];

        foreach($cars as $car){
            $element = [
                'color'=>$car->getColor(),
                'model'=>$car->getModel(),
                'seat'=>$car->getSeat(),
                'year'=>$car->getYear(),
                'id'=>$car->getId(),
                'numberplate'=>$car->getNumberPlate(),
            ];
            array_push($dataToReturn,$element);
        }
        return $this->json($dataToReturn);
    }
    /**
     * @Route("/add", name="new")
     */
    public function new(ManagerRegistry $doctrine, Request $request):Response
    {
        $entityManager = $doctrine->getManager();
        $newPostJson = json_decode($request->getContent(), true);

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $car = new Car();
        $user = $doctrine->getRepository(User::class)->find($newPostJson['idUser']);


        $car->setColor($newPostJson['color']);
        $car->setModel($newPostJson['model']);
        $car->setYear(intval($newPostJson['year']));
        $car->setSeat($newPostJson['seat']);
        $car->setIdUser($user);
        $car->setNumberplate($newPostJson['numberplate']);

        $car->setCreatedAt($today);
        $car->setUpdatedAt($today);

        $entityManager->persist(($car));

        $entityManager->flush();
        return new Response('Saved new ar with id '. $car->getId());
    }

    /**
     * @Route("/delete/{car_id}", name="new")
     */
    public function delete(ManagerRegistry $doctrine, Request $request, $car_id):Response
    {
        try {
            $entityManager = $doctrine->getManager();
            $car = $doctrine
                ->getRepository(Car::class)
                ->find($car_id);

            $entityManager->remove($car);

            $entityManager->flush();
            return $this->json([
                'code' => '1',
                'path' => 'Car delete',
            ]);
        }
        catch (Exception $e){
            return $this->json([
                'code' => '0',
                'error' => 'Car already link to a travel.',
            ]);
        }
    }

    /**
     * @Route("/delete/{car_id}", name="new")
     */
    public function update(ManagerRegistry $doctrine, Request $request, $car_id):Response
    {
            $entityManager = $doctrine->getManager();
            $car = $doctrine
                ->getRepository(Car::class)
                ->find($car_id);

            $entityManager->remove($car);

            $entityManager->flush();
            return $this->json([
                'code' => '1',
                'path' => 'Car delete',
            ]);
    }
}
