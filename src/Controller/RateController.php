<?php

namespace App\Controller;

use App\Entity\Rate;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rate", name="rate_")
 */
class RateController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RateController.php',
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $newPostJson = json_decode($request->getContent(), true);

        // check if rate already exists

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $rate = new Rate();

        $userFrom = $doctrine->getRepository(User::class)->find($newPostJson['idFrom']);
        $userTo = $doctrine->getRepository(User::class)->find($newPostJson['idTo']);

        $rate->setComment($newPostJson['comment']);
        $rate->setRate($newPostJson['rate']);
        $rate->setTravelId($newPostJson['travelId']);

        $rate->setIdUserRating($userFrom);
        $rate->setIdUserRated($userTo);

        $rate->setCreatedAt($today);
        $rate->setUpdatedAt($today);

        $entityManager->persist(($rate));

        $entityManager->flush();

        return $this->json([
            'code' => '1',
            'message' => 'Saved new notation with id '. $rate->getId(),
        ]);


    }

    /**
     * @Route("/user/{user_id}", name="retrieveUser")
     */
    public function retrieveByUser(ManagerRegistry $doctrine, Request $request, int $user_id): Response
    {

        $from = $doctrine->getRepository(Rate::class)->findBy(['IdUserRating'=>$user_id]);
        $to = $doctrine->getRepository(Rate::class)->findBy(['IdUserRated'=>$user_id]);

        $dataToReturn = [
            'from' => [],
            'to' => [],
        ];

        $total = 0;
        foreach ($to as $rate) {
            $total += $rate->getRate();

            $user = $doctrine->getRepository(User::class)->findBy(['id'=>$rate->getIdUserRated()]);
            $user = $user[0];
            $dataRate = [
                'id'=>$rate->getId(),
                'rate'=>$rate->getRate(),
                'comment'=>$rate->getComment(),
                'ratedUser'=>[
                    'id'=>$user->getId(),
                    'name'=>$user->getName(),
                    'firstName'=>$user->getFirstName(),
                ]
            ];
            array_push($dataToReturn['to'], $dataRate);
        }

        foreach ($from as $rate){
            $user = $doctrine->getRepository(User::class)->findBy(['id'=>$rate->getIdUserRated()]);
            $user = $user[0];
            $dataRate = [
                'id'=>$rate->getId(),
                'rate'=>$rate->getRate(),
                'comment'=>$rate->getComment(),
                'ratedUser'=>[
                    'id'=>$user->getId(),
                    'name'=>$user->getName(),
                    'firstName'=>$user->getFirstName(),
                ]
            ];
            array_push($dataToReturn['from'], $dataRate);
        }

        if (count($to)){
            $dataToReturn['average'] =  $total / count($to);
            $dataToReturn['quantity'] =  count($to);
        } else {
            $dataToReturn['average'] = 0;
            $dataToReturn['quantity'] = 0;
        }
        return $this->json($dataToReturn);
    }
}
