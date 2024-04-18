<?php

namespace App\Controller;

use App\Entity\CartLine;
use App\Entity\CommandLine;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cart;
use App\Entity\Product;

//use App\Form\CartType;
use App\Entity\User;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

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


        if ($user) {
            return $this->render('cart/index.html.twig', [
                'controller_name' => 'CartController',
                'cart' => $cart,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
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

        $cart->updateTotal();

        // Sauvegarder les modifications
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('cart_index');
    }

    #[Route("/cart/update", name: "update_cart", methods: ['GET', 'POST'])]
    public function updateCart(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $cart = $user->getUserCart();

        $productQty = $request->request->get('qty');
        $productUpdateId = $request->request->get('updateQtt');
        if ($productUpdateId) {
            if ($cart === null) {
                // Page pour dire que le panier est vide qui n'existe pas encore
                return $this->redirectToRoute('cart_empty');
            }

            // Parcourir les lignes de panier et mettre à jour les quantités
            foreach ($cart->getCartLines() as $cartLine) {
                if ($cartLine->getId() == $productUpdateId) {
                    $cartLine->setQuantity($productQty);
                    $entityManager->persist($cartLine);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        } else {
            $productUpdateId = $request->request->get('deleteCartLine');
            foreach ($cart->getCartLines() as $cartLine) {
                if ($cartLine->getId() == $productUpdateId) {
                    if ($cartLine->getCartId() !== $cart) {
                        throw $this->createAccessDeniedException('La ligne de panier demandée ne fait pas partie de votre panier.');
                    }
                    $entityManager->remove($cartLine);
                    $entityManager->flush();
                }
            }

            return $this->redirectToRoute('cart_index');
        }

    }

    #[Route('/cart/command', name: 'commande', methods: ['GET', 'POST'])]
    public function makeCartOrder(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        $user = $this->getUser();
        $cart = $user->getUserCart();

        $order = new Order();
        $order->setCart($cart);
        $orderNumb = $user->getOrders();
        $date = new \DateTime();
        $order->setDateTime($date);
        $order->setOrderNumber(count($orderNumb)+1);

        $order->setValid(true);

        $order->setUser($user);

        foreach ($cart->getCartLines() as $line){
            $commandLine = new CommandLine();
            $commandLine->setQuantity($line->getQuantity());

            $product = $line->getProduct();
            $commandLine->setProductName($product);

            $commandLine->setOrderNumber($order);

            $order->addCommandLine($commandLine);
            $entityManager->remove($line);
            $entityManager->persist($commandLine);
        }
        $entityManager->persist($order);
        $entityManager->flush();


        return $this->redirectToRoute('order_final',['orderId' => $order->getId()] );
    }


}
