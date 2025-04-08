<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface      $entityManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username'], $data['email'], $data['password'])) {
            return new JsonResponse(['message' => 'Username, email en password zijn verplicht.'], Response::HTTP_BAD_REQUEST);
        }

        if (strlen($data['username']) < 3 || strlen($data['username']) > 20) {
            return new JsonResponse(['message' => 'Gebruikersnaam moet tussen 3 en 20 tekens zijn.'], Response::HTTP_BAD_REQUEST);
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            return new JsonResponse(['message' => 'Gebruikersnaam mag alleen letters, cijfers en underscores bevatten.'], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'Ongeldig e-mailadres.'], Response::HTTP_BAD_REQUEST);
        }

        if (strlen($data['password']) < 8 ||
            !preg_match('/[A-Z]/', $data['password']) ||
            !preg_match('/[a-z]/', $data['password']) ||
            !preg_match('/[0-9]/', $data['password'])
        ) {
            return new JsonResponse([
                'message' => 'Wachtwoord moet minstens 8 tekens lang zijn, en minstens 1 hoofdletter, 1 kleine letter en 1 cijfer bevatten.'
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']])) {
            return new JsonResponse(['message' => 'E-mailadres is al in gebruik.'], Response::HTTP_CONFLICT);
        }

        if ($entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']])) {
            return new JsonResponse(['message' => 'Gebruikersnaam is al in gebruik.'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Gebruiker succesvol geregistreerd.'], Response::HTTP_CREATED);
    }

}
