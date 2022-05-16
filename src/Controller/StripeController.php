<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(ProductRepository $repository, Cart $cart)
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $cartData = $cart->completeCart($repository);

        foreach($cartData as $key1 => $product) {
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()]
                    ]
                ],
                'quantity' => $product['quantity']
            ];
        }

        Stripe::setApiKey('sk_test_51Kz1a6GoHUymLVSOp9nPnMhprpazmzKLL9VJZUXDTKB1jV6aOsgeHRftDwDK1lA14RzaSNNN4hHiJ66reCjSk7oq00Xrkq7Ppj');

        $checkout_session = Session::create([
            /* 'payment_method_types' => ['card'], */ //enabled by default
            'line_items' => [
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                /* 'price' => '{{PRICE_ID}}', */
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        return $this->redirect($checkout_session->url, 303);
    }
}
