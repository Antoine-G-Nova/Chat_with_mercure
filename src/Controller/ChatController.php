<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\MercureCookieGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ChatController extends AbstractController
{
    /**
     * @Route("/", name="chat")
     */
    public function index(MercureCookieGenerator $cookieGenerator)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $token = $cookieGenerator->generateToken($this->getUser());

        return $this->render('chat/index.html.twig', [
            'token' => $token
        ]);
    }

    /**
     *
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     *
     * @Route("/get-users", name="get_user")
     */
    public function getAllUser(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();
        $users = $serializer->serialize($users, 'json');
        $users = json_decode($users, true);
        $connectedUser = $this->getUser();
        $users = array_filter($users, function ($user) use ($connectedUser) {
            return $user['id'] != $connectedUser->getId();
        });
        $users = array_values($users);
        $users['connected'] = [
            'username' => $connectedUser->getUsername(),
            'id' => $connectedUser->getId()
        ];

        return new JsonResponse($users, 200, []);
    }
}
