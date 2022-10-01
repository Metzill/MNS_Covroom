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
        $newPostJson = json_decode($request->getContent(), true);

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $rate = new Rate();

        $userFrom = $doctrine->getRepository(User::class)->find($newPostJson['idFrom']);
        $userTo = $doctrine->getRepository(User::class)->find($newPostJson['idTo']);

        $rate->setComment($newPostJson['comment']);
        $rate->setRate($newPostJson['rate']);

        $rate->setIdUserRating($userFrom);
        $rate->setIdUserRated($userTo);

        $rate->setCreatedAt($today);
        $rate->setUpdatedAt($today);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RateController.php',
        ]);
    }
}
