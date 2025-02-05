<?php

namespace App\Controller;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController{
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/complete', name: 'app_complete', methods: ['POST'])]
    public function complete(Request $request, EntityManagerInterface $em): Response
    {
        $data = $request->request;
        if (!empty($data->get('username')) && !empty($data->get('fullname'))) {
            $user = $this->getUser();
            $user
                ->setUsername($data->get('username'))
                ->setFullname($data->get('fullname'))
                ;
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil est complété');

        } else {
            $this->addFlash('error', 'Veuillez remplir tous les champs');
        }
        return $this->render('app_profile');
    }
}
