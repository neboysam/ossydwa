<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use DateTimeImmutable;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request, ProductRepository $repository): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('add_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->completeCart($repository)
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap")
     */
    public function add(Cart $cart, Request $request, ProductRepository $repository, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            //dd($form->get('addresses')->getData());
            /* dd($form->getData()); */
            /* dd($form->get('carriers')->getData()); */
            /* dd($form->get('addresses')->getData()); */
            $date = new \DateTimeImmutable();
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery->getName());
            $order->setIsPaid(false);
            $manager->persist($order);
            $manager->flush();
            
            $orderDetails = new OrderDetails();

            $cartData = $cart->completeCart($repository);

            foreach($cartData as $key1 => $data) {
                /* dd($data['quantity']); */
                /* dd($data['product']->getName()); */
                /* dd($data['product']->getPrice()); */
                /* foreach($elements as $key2 => $element) {
                    dd($element);
                } */
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($data['product']->getName());
                $orderDetails->setQuantity($data['quantity']);
                $orderDetails->setPrice($data['product']->getPrice());
                $orderDetails->setTotal($data['quantity'] * $data['product']->getPrice());
                $manager->persist($orderDetails);
                $manager->flush();
            }
        }

        return $this->render('order/order_summary.html.twig', [
            'cart' => $cart->completeCart($repository),
            'carrierPrice' => $carriers->getPrice(),
            'carriers' => $carriers,
            'delivery' => $delivery
        ]);
    }
}
