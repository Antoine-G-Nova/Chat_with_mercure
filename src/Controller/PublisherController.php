<?php

namespace App\Controller;

use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class PublisherController extends AbstractController
{
    /**
     * @Route("/publish", name="publisher")
     */
    public function index(Request $request, MessageBusInterface $bus)
    {
        $data = json_decode($request->getContent());
        $to = $data->to;
        $targets = [
            "http://exemple.com/user/{$this->getUser()->getId()}",
            "http://exemple.com/user/{$to}"
        ];

        $update = new Update(
            'http://chat.localhost',
            json_encode(
                [
                    'message' => $data->message,
                    'to'      => $to,
                    'from'    => [
                        'username' => $this->getUser()->getUsername(),
                        'id'       => $this->getUser()->getId()
                    ]
                ]
            ),
            $targets
        );

        $bus->dispatch($update);

        return new JsonResponse(['status' => 'published!']);
    }
}
