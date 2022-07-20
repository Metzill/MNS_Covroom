<?php

namespace App\Controller;

use App\Entity\TravelPreference;
use App\Repository\TravelPreferenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api_")
 */
class TravelPreferenceController extends AbstractController
{
    /**
     * @Route("/travel_preference", name="index_travel_preference")
     */
    public function index(ManagerRegistry $doctrine, TravelPreferenceRepository $TravelPreferenceRepository): Response
    {
        $travel_preferences = $TravelPreferenceRepository->findAll();

        $travel_preference_data = [];

        foreach ($travel_preferences as $travel_preference) {
            $travel_preference_data[] = [
                'id' => $travel_preference->getIdTravelPreference(),
                'name' => $travel_preference->getName(),
                'picture' => $travel_preference->getPicture(),
            ];
         }
  
         return $this->json($travel_preference_data); 
    }
    
    /**
     * @Route("/travel_preference/create", name="new_travel_preference")
     */
    public function createTravelPreference(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $travel_preference = new TravelPreference();
        $travel_preference->setName('PreferenceDeTest');
        $travel_preference->setPicture('LienVersImage');

        $entityManager->persist(($travel_preference));

        $entityManager->flush();
        
        return new Response('Saved new travel_preference with id '.$travel_preference->getIdTravelPreference());
    }
}
