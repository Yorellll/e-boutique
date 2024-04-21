<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CartRepository;


#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'cartItemCount' => $cartItemCount,
        ]);
    }

    #[Route('/admin/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager, CartRepository $cartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_category_show_products', methods: ['GET'])]
    public function showProducts(Category $category, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }

        return $this->render('product/categories.html.twig', [
            'controller_name' => 'CategoryController',
            'tab' => [],
            'categ' => $category,
            'products' => $category->getProducts(),
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
