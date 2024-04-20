<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CartRepository;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;



#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, CartRepository $cartRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/all', name: 'app_product_all', methods: ['GET'])]
    public function all(ProductRepository $productRepository, CartRepository $cartRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('product/products.html.twig', [
            'products' => $productRepository->findAll(),
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository, CategoryRepository $categoryRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le fichier uploadé
            $imgFile = $form->get('img')->getData();

            if ($imgFile) {
                // Donnez un nom unique au fichier
                $newFilename = uniqid() . '.' . $imgFile->guessExtension();

                // Déplacez le fichier vers le répertoire où vous souhaitez stocker les images
                try {
                    $imgFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement du fichier échoue
                    // Par exemple, enregistrer l'erreur dans des logs
                }

                // Mettez à jour le champ 'img' pour stocker le nom du fichier dans la base de données
                $product->setImg($newFilename);
            }

            // Enregistrer le produit en base de données
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product, CartRepository $cartRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, CartRepository $cartRepository, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le fichier uploadé
            $imgFile = $form->get('img')->getData();

            if ($imgFile) {
                // Donnez un nom unique au fichier
                $newFilename = uniqid() . '.' . $imgFile->guessExtension();

                // Déplacez le fichier vers le répertoire où vous stockez les images
                try {
                    $imgFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement du fichier échoue
                    // Par exemple, enregistrer l'erreur dans des logs
                }

                // Mettez à jour le champ 'img' pour stocker le nom du fichier dans la base de données
                $product->setImg($newFilename);
            }

            // Enregistrez les modifications du produit en base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/category/{id}', name: 'app_product_by_category', methods: ['GET'])]
    public function productsByCategory(Category $category, ProductRepository $productRepository, CartRepository $cartRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['User' => $user]);

        if (!$cart) {
            $cartItemCount = 0;
        } else {
            $cartItemCount = $cart->getCartLines()->count();
        }

        $products = $productRepository->findBy(['category' => $category]);

        return $this->render('product/products_by_category.html.twig', [
            'products' => $products,
            'cartItemCount' => $cartItemCount,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
