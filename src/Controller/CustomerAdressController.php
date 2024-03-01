<?php

namespace App\Controller;

use App\Entity\CustomerAdress;
use App\Form\CustomerAdressType;
use App\Repository\CustomerAdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer/adress')]
class CustomerAdressController extends AbstractController
{
    #[Route('/', name: 'app_customer_adress_index', methods: ['GET'])]
    public function index(CustomerAdressRepository $customerAdressRepository): Response
    {
        return $this->render('customer_adress/index.html.twig', [
            'customer_adresses' => $customerAdressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_customer_adress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customerAdress = new CustomerAdress();
        $form = $this->createForm(CustomerAdressType::class, $customerAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customerAdress);
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer_adress/new.html.twig', [
            'customer_adress' => $customerAdress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_adress_show', methods: ['GET'])]
    public function show(CustomerAdress $customerAdress): Response
    {
        return $this->render('customer_adress/show.html.twig', [
            'customer_adress' => $customerAdress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customer_adress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomerAdress $customerAdress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerAdressType::class, $customerAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer_adress/edit.html.twig', [
            'customer_adress' => $customerAdress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_adress_delete', methods: ['POST'])]
    public function delete(Request $request, CustomerAdress $customerAdress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customerAdress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customerAdress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_adress_index', [], Response::HTTP_SEE_OTHER);
    }
}
