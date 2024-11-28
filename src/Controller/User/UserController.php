<?php

namespace App\Controller\User;

use App\Entity\Prestation;
use App\Entity\Category;
use App\Entity\Message; // Correction de l'import
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/dashboard', name: 'user')]
    public function show(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Supposons que vous souhaitez récupérer la première prestation pour simplifier
        
        $prestation = $entityManager->getRepository(Prestation::class)->findOneBy([]);
        
        if (!$prestation) {
            throw $this->createNotFoundException('Prestation not found.');
        }

        // Récupérer toutes les catégories
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
        // Vérifier que la requête contient bien des données JSON
        $data = json_decode($request->getContent(), true);

        if (!isset($data['content']) || empty($data['content'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Content is required.'
            ], Response::HTTP_BAD_REQUEST);
        }

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

    // Route pour ajouter un produit au panier sans utiliser CartItem
    #[Route('/api/cart/add', name: 'api_cart_add', methods: ['POST'])]
    public function addToCart(
        Request $request,
        SessionInterface $session,
        EntityManagerInterface $entityManager // Ajouter EntityManagerInterface ici
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Vérifier si un ID de prestation a été envoyé dans la requête
        if (!isset($data['prestationId']) || empty($data['prestationId'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Prestation ID is required.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer la prestation par son ID
        $prestation = $entityManager->getRepository(Prestation::class)->find($data['prestationId']);

        if (!$prestation) {
            return $this->json([
                'status' => 'error',
                'message' => 'Prestation not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Ajouter l'article au panier dans la session
        $cart = $session->get('cart', []);
        
        // Vérifier si la prestation est déjà dans le panier
        if (isset($cart[$prestation->getId()])) {
            $cart[$prestation->getId()]['quantity'] += 1; // Incrémenter la quantité
        } else {
            $cart[$prestation->getId()] = [
                'prestationId' => $prestation->getId(),
                'quantity' => 1
            ];
        }

        // Sauvegarder le panier dans la session
        $session->set('cart', $cart);

        return $this->json([
            'status' => 'success',
            'message' => 'Prestation added to cart',
            'cart' => $cart
        ]);
    }

    // Route pour récupérer le contenu du panier
    #[Route('/api/cart', name: 'api_cart', methods: ['GET'])]
    public function viewCart(SessionInterface $session): JsonResponse
    {
        $cart = $session->get('cart', []);
        return $this->json([
            'cart' => $cart,
        ]);
    }
}
