<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription', methods: ['POST'])]
    public function subscription(Request $request, PaymentService $ps): Response
    {
        $subscription = $this->getUser()->getSubscription();

        if ($subscription == null || $subscription->isActive() === false) {
            $checkoutUrl = $ps->setPayment(
                $this->getUser(),
                intval($request->get('plan'))
            );
            return $this->redirect($checkoutUrl);
        }

        $this->addFlash('warning', "Vous êtes déjà abonné(e)");
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/subscription/success', name: 'app_success')]
    public function success(): Response
    {
        $this->addFlash('success', "Votre abonnement a bien été pris en compte");
        return $this->redirectToRoute('app_profile');
    }



    #[Route('/subscription/cancel', name: 'app_cancel')]
    public function cancel(): Response
    {
        $this->addFlash('warning', "Une erreur est survenue lors de la transaction");
        return $this->redirectToRoute('app_profile');
    }
}
