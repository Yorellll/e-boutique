<?php

namespace App\Controller;

use App\Entity\CartLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cart;
use App\Entity\Product;
//use App\Form\CartType;
use App\Entity\User;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
//    #[Route('/cart', name: 'app_cart')]
//    public function index(): Response
//    {
//        return $this->render('cart/index.html.twig', [
//            'controller_name' => 'CartController',
//        ]);
//    }

    #[Route('/cart', name: 'cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $cart = $cartRepository->findOneBy(['User' => $user]); // Trouver le panier de l'utilisateur connecté

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{productId}', name: 'cart_add_product', methods: ['GET', 'POST'])]
    public function addToCart(Request $request, $productId, EntityManagerInterface $entityManager, CartRepository $cartRepository): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
//        $cart = $user->getUserCart(); // Récupérer le panier de l'utilisateur
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$user) {
            throw $this->createNotFoundException('Le user nest pas trouver.');
        }

        // Votre logique pour ajouter un produit au panier ici
        // Vous pouvez utiliser $productId pour récupérer le produit à ajouter
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas.');
        }

        $cartLine = new CartLine();
        $cartLine->setProduct($product);
        $cartLine->setQuantity(1);

        $cart->addCartLine($cartLine);

//        $cart->updateTotal();

        // Sauvegarder les modifications
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{cartLineId}', name: 'cart_remove_product', methods: ['GET', 'POST'])]
    public function removeFromCart(Request $request, $cartLineId): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $cart = $user->getUserCart(); // Récupérer le panier de l'utilisateur

        // Votre logique pour supprimer un produit du panier ici
        // Vous pouvez utiliser $cartLineId pour récupérer la ligne de panier à supprimer

        // Sauvegarder les modifications
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('cart_index');
    }



}
