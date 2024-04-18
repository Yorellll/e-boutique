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

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET', 'POST'])]
    public function index(OrderRepository $orderRepository, Request $request): Response
    {
        $user = $this->getUser();// Récupérer l'utilisateur connecté

        if ($user){
            $cart = $user->getUserCart();
            if ($cart){
                return $this->render('order/index.html.twig', [
                    'orders' => $orderRepository->findAll(),
                ]);
            }else{
                return $this->redirectToRoute('app_login');
            }
        }else{
            return $this->redirectToRoute('app_login');
        }

    }

    #[Route('/merci', name: 'order_final', methods: ['GET'])]
    public function final(OrderRepository $orderRepository, Request $request): Response
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

        return $this->render('order/thx.html.twig', [
            'orders' => $currentOrder,
        ]);

    }

    #[Route('/detail', name: 'order_details', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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

        return $this->render('order/details.html.twig', [
            'order' => $currentOrder,
        ]);
    }


    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function newOrder(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
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
