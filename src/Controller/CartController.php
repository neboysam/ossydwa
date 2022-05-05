<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(ProductRepository $repository, Cart $cart): Response
    {
        //dd($cart->get()); //gives > array:1 [▼ 1 => 1] i.e. id => quantity
        $completeCart = [];
        if ($cart->get()) {
            foreach ($cart->get() as $id => $quantity) {
                $completeCart[] = [
                    'quantity' => $quantity,
                    /* 'products' => $this->entityManager->getRepository(Product::class)->findById($id) */
                    'product' => $repository->findOneById($id)
                ];
            }
        }

        return $this->render('cart/cart.html.twig', [
            'cart' => $completeCart
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id): Response
    {
        //dd($id);
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_from_cart")
     */
    public function remove(Cart $cart): Response
    {
        //dd($id);
        $cart->remove();
        return $this->redirectToRoute('products');
    }
}
