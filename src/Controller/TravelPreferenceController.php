<?php

namespace App\Controller;

use App\Entity\TravelPreference;
use App\Repository\TravelPreferenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/travel_preference", name="travel_preference_")
 */
class TravelPreferenceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $travel_preferences = $doctrine
        ->getRepository(TravelPreference::class)
        ->findAll();

        $travel_preference_data = [];

        foreach ($travel_preferences as $travel_preference) {
            $travel_preference_data[] = [
                'id' => $travel_preference->getId(),
                'name' => $travel_preference->getName(),
                'picture' => $travel_preference->getPicture(),
            ];
         }
  
         return $this->json($travel_preference_data); 
    }
    
    /**
     * @Route("/new", name="new")
     */
    public function createTravelPreference(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $travel_preference = new TravelPreference();
        $travel_preference->setName('PreferenceDeTest');
        $travel_preference->setPicture('LienVersImage');

        $entityManager->persist(($travel_preference));

        $entityManager->flush();
        
        return new Response('Saved new travel_preference with id '.$travel_preference->getId());
    }
}
