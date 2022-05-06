<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/compte/addresses", name="account_addresses")
     */
    public function index(): Response
    {
        return $this->render('account/addresses.html.twig');
    }
}
