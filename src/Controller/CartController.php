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
    public function addToCart(Cart $cart, $id): Response
    {
        //dd($id);
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function removeCart(Cart $cart): Response
    {
        //dd($id);
        $cart->remove();
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_from_cart")
     */
    public function deleteFromCart(Cart $cart, $id): Response
    {
        //dd($id);
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/subtract/{id}", name="subtract_from_cart")
     */
    public function subtractFromCart(Cart $cart, $id): Response
    {
        //dd($id);
        $cart->subtract($id);
        return $this->redirectToRoute('cart');
    }
}
