<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CartRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;





#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/admin', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $user2 = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user2]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, CartRepository $cartRepository,CategoryRepository $categoryRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user,
                $user->getPassword());
            $user->setPassword($hashedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager,CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/monCompte', name: 'app_user_profile', methods: ['GET', 'POST'])]
    public function monCompte(Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository,CategoryRepository $categoryRepository,UserRepository $userRepository,ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
        }
        $user2 = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user2]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('user/users.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
            'users' => $userRepository->findAll(),
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/monCompte/edit', name: 'app_user_profile_edit', methods: ['GET', 'POST'])]
    public function userEdit(Request $request, EntityManagerInterface $entityManager,CartRepository $cartRepository, UserPasswordHasherInterface $userPasswordHasher,CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_user_profile');
        }
        $user2 = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user2]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }

        return $this->render('user/editUser.html.twig', [
            'form' => $form->createView(),
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
