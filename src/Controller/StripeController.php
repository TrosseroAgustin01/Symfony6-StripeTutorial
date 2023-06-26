<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Charge;
use Stripe\Stripe;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }
//* false data card:"4000 0025 0000 1001"
/* $request->request->get('stripeToken') */
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        try {
            Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
            Charge::create([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get("stripeToken"),
                "description" => "Binaryboxtuts Payment Test"
            ]);
            #var_dump($request->request->get("stripeToken"));
            $this->addFlash(
                'success',
                'Payment Successful!'
            );
        } catch (\Stripe\Exception\CardException $e) {
            $e->getError();
        }

        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
    }
}


