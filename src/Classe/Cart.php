<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add($id)
    {
        /* $cart = $this->session->get('cart'); 
        Gives null all the time if the cart is empty
        */
        $cart = $this->session->get('cart', []);

        /* 
        dd($cart);
        Gives an empty array if nothing has been added to the cart beforehand
        Upon page refresh:
        Cart.php on line 51:
        array:1 [▼
        2 => 1
        ]

        So, $cart = [ 'id' => quantity ]
        */

        /*
        $this->session->set('cart', [
            [
                'id' => $id,
                'quantity' => 1
            ]
        ]);

        dd($cart):
        Cart.php on line 20:
            array:2 [▼
            0 => array:2 [▼
                "id" => "1"
                "quantity" => 1
            ]
            1 => 3
            ]
        Hence, the empty array on l.18 is the following one: id => quantity. In this example, 1 => 3. So, $card[id] => quantity
        */

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        //dd($cart);
        $this->session->set('cart', $cart);
        //dd($cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }
}