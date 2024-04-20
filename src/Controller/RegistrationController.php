<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Form\RegistrationFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;



class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,CartRepository $cartRepository,CategoryRepository $categoryRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']); //psw de l'admin rootuser

            $cart = new Cart();
            $dayTime= new DateTime();
            $cart->setCreationDate($dayTime);
            $user->setCart($cart);
            $userCart = new Cart();
            $userCart = $user->getUserCart();
            if (!$userCart){
                error_log("pas de cart");
            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_default');
        }
        $user2 = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user2]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('registration/register.html.twig', [
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
