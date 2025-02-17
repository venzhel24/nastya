<?php

namespace App\Controller;

use App\Dto\ClearCacheMessage;
use App\MessageHandler\ClearCacheHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->render('main/home.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/admin/cache/clear', name: 'admin_clear_cache', methods: ['POST'])]
    public function clearCache(MessageBusInterface $bus): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $taskId = uniqid('task_', true);

        for ($i = 0; $i < 20; $i++) {
            $bus->dispatch(new ClearCacheMessage($taskId));
        }

        return new JsonResponse(['status' => 'queued', 'taskId' => $taskId]);
    }
}
