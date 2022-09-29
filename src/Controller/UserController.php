<?php

namespace App\Controller;

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
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $users = $doctrine
        ->getRepository(User::class)
        ->findAll();

        $user_data = [];

        foreach ($users as $user) {

            $created_at_dt = $user->getCreatedAt();
            $created_at_dt->setTimezone(new DateTimeZone('Europe/Paris'));

            $updated_at_dt = $user->getUpdatedAt();
            if($updated_at_dt != null) $updated_at_dt->setTimezone(new DateTimeZone('Europe/Paris'));

            $deleted_at_dt = $user->getDeletedAt();
            if($deleted_at_dt != null) $deleted_at_dt->setTimezone(new DateTimeZone('Europe/Paris'));

            $user_data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'firstName' => $user->getFirstname(),
                'phoneNumber' => $user->getPhoneNumber(),
                'gender' => $user->getGender(),
                'age' => $user->getAge(),
                'profilPicture' => $user->getProfilePicture(),
                'description' => $user->getDescription(),
                'mailConfirm' => $user->isMailConfirmation(),
                'role' => $user->getRole(),
                'cars' => $user->getCars(),
                'travels' => $user->getTravels(),
                'favorites' => $user->getFavorites(),
                'ratingWritten' => $user->getRatingWritten(),
                'ratingReceived' => $user->getRatingReceived(),
                'created_at' => gmdate("Y-m-d H:i:s e", strtotime($created_at_dt)),
                'updated_at' => gmdate("Y-m-d H:i:s e", strtotime($updated_at_dt)),
                'deleted_at' => gmdate("Y-m-d H:i:s e", strtotime($deleted_at_dt)),
            ];
         }

         return $this->json($user_data);
    }

    /**
     * @Route("/new", name="new")
     */
    public function createUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $newPostJson = json_decode($request->getContent(), true);
        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $user = new User();
        $user->setEmail($newPostJson['email']);
        $user->setName($newPostJson['name']);
        $user->setFirstname($newPostJson['firstName']);
//        $user->setPassword($newPostJson['password']);
        $user->setPassword(password_hash($newPostJson['password'], PASSWORD_DEFAULT));
        $user->setGender($newPostJson['gender']);
//        $user->setAge($newPostJson['age']);
        $user->setAge(0);
//        $user->setProfilePicture($newPostJson['profilePicture']);
        $user->setProfilePicture('null');
        $user->setPhoneNumber($newPostJson['phoneNumber']);
        $user->setRole($newPostJson['role']);
        $user->setMailConfirmation(0);
        $user->setCreatedAt($today);

        $entityManager->persist(($user));

        $entityManager->flush();

        return new Response('Saved new user with id '.$user->getId());
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function updateTravelPreference(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $PutJson = json_decode($request->getContent(), true);

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $user = $entityManager
        ->getRepository(User::class)
        ->find($id);

        if(array_key_exists('email',$PutJson)) $user->setEmail($PutJson['email']);
        if(array_key_exists('password',$PutJson)) $user->setPassword($PutJson['password']);
        if(array_key_exists('profilePicture',$PutJson)) $user->setProfilePicture($PutJson['profilePicture']);
        if(array_key_exists('phoneNumber',$PutJson)) $user->setPhoneNumber($PutJson['phoneNumber']);
        if(array_key_exists('role',$PutJson)) $user->setRole($PutJson['role']);
        if(array_key_exists('description',$PutJson)) $user->setDescription($PutJson['description']);
        if(array_key_exists('mailConfirm',$PutJson)) $user->setMailConfirmation($PutJson['mailConfirm']);
        $user->setUpdatedAt($today);

        $entityManager->persist(($user));

        $entityManager->flush();

        $user_data[] = [
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'profilPicture' => $user->getProfilePicture(),
            'description' => $user->getDescription(),
            'mailConfirm' => $user->isMailConfirmation(),
            'role' => $user->getRole(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt()
        ];

        return $this->json($user_data);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $newPostJson = json_decode($request->getContent(), true);

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone("UTC"));

        $user = $entityManager
            ->getRepository(User::class)
            ->findBy(['email'=>$newPostJson['email']]);
        $user = $user[0];

        if (!password_verify($newPostJson['password'],$user->getPassword())){
            return $this->json([
                'code' => '0',
                'path' => 'Password not match',
            ]);
        }

        $dataToReturn = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'profilPicture' => $user->getProfilePicture(),
            'description' => $user->getDescription(),
            'mailConfirm' => $user->isMailConfirmation(),
            'name' => $user->getName(),
            'firstName' => $user->getFirstname(),
            'role' => $user->getRole(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt()
        ];

        return $this->json($dataToReturn);
    }


    /**
     * @Route("/retrieve/{user_id}", name="retrieve")
     */
    public function retrieve(ManagerRegistry $doctrine, $user_id): Response
    {
        $user = $doctrine
            ->getRepository(User::class)
            ->find($user_id);

        $dataToReturn = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'profilPicture' => $user->getProfilePicture(),
            'description' => $user->getDescription(),
            'mailConfirm' => $user->isMailConfirmation(),
            'name' => $user->getName(),
            'firstName' => $user->getFirstname(),
            'role' => $user->getRole(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt()
        ];

        return $this->json($dataToReturn);
    }
}
