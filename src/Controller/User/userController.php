<?php

namespace App\Controller\User;

use App\Entity\Prestation;
use App\Entity\Category;
use App\Entity\Message;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/dashboard/{id}', name: 'app_user_dashboard')]
    public function show(
        int $id,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $prestation = $entityManager->getRepository(Prestation::class)->find($id);

        if (!$prestation) {
            throw $this->createNotFoundException('Prestation not found.');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('User/index.html.twig', [
            'prestation' => $prestation,
            'categories' => $categories,
        ]);
    }


    #[Route('/api/messages', name: 'api_messages_send', methods: ['POST'])]
    public function sendMessage(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $message = new Message();
        //$message->setContent($data['content'] ?? '');
        //$message->setIsAdmin(false);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json([
            'id' => $message->getId(),
          //  'content' => $message->getContent(),
            ///'isAdmin' => $message->getIsAdmin(),
            'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }
}
