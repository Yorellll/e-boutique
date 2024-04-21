<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;



#[Route('/order')]
class OrderController extends AbstractController
{

    #[Route('/', name: 'app_order_index', methods: ['GET', 'POST'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $order = $user->getOrders();

        if ($user){
            $cart = $user->getUserCart();
            if ($cart){
                $cartItemCount = 0;
                return $this->render('order/index.html.twig', [
                    'orders' => $order,

                    'cartItemCount' => $cartItemCount,
                    'categories' => $categoryRepository->findAll(),
                ]);
            }else{
                $cartItemCount = $cart->getCartLines()->count();
                return $this->redirectToRoute('app_login');
            }
        }else{
            return $this->redirectToRoute('app_login');
        }

    }
    

    #[Route('/merci', name: 'order_final', methods: ['GET'])]
    public function final(Request $request, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $order = $user->getOrders();
        $orderId = $request->query->get('orderId');
        $currentOrder = new Order();
        $cart = $cartRepository->findOneBy(['User' => $user]);
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        foreach ($order as $orderTab){
            if ($orderTab->getId() == $orderId){
                $currentOrder = $orderTab;
            }
        }

        return $this->render('order/thx.html.twig', [
            'orders' => $currentOrder,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);

    }

    #[Route('/detail', name: 'order_details', methods: ['GET', 'POST'])]
    public function new(Request $request,CategoryRepository $categoryRepository, CartRepository $cartRepository): Response
    {

        $user = $this->getUser();
        $order = $user->getOrders();
        $orderId = $request->query->get('orderId');

        $currentOrder = new Order();
        foreach ($order as $orderTab){
            if ($orderTab->getId() == $orderId){
                $currentOrder = $orderTab;
            }
        }
        $user2 = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user2]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('order/details.html.twig', [
            'order' => $currentOrder,
            'categories' => $categoryRepository->findAll(),
            'cartItemCount' => $cartItemCount,
        ]);
    }


    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function newOrder(Request $request, EntityManagerInterface $entityManager,CategoryRepository $categoryRepository, CartRepository $cartRepository): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
            'cartItemCount' => $cartItemCount,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order,CategoryRepository $categoryRepository, CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('order/show.html.twig', [
            'order' => $order,
            'categories' => $categoryRepository->findAll(),
            'cartItemCount' => $cartItemCount,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager,CategoryRepository $categoryRepository, CartRepository $cartRepository): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
            'cartItemCount' => $cartItemCount,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
