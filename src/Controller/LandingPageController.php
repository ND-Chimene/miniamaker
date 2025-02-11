<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class LandingPageController extends AbstractController
{
    #[Route('/landing/add', name: 'lp_add')]
    public function index(): Response
    {

        $user = $this->getUser()->getRoles()[0];

        if (!$user('ROLE_AGENT') && !$user('ROLE_PRO')) {
            return $this->redirectToRoute('app_detail');
        }

        return $this->render('landing_page/index.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }
}
