<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController{
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em, UploaderService $us): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (true) { // TODO: Vérification de mot de passe
                $image = $form->get('image')->getData(); // Récupère l'image
                if ($image != null) { // Si l'image est téléversée
                    $user->setImage( // Méthode de mutation de l'image
                        $us->uploadFile( // Méthode de téléversement
                            $image, // Image téléversée
                            $user->getImage() // Image actuelle
                            )
                    );
                }

            $em->persist($user);
            $em->flush();

            // Redirection avec flash message
            $this->addFlash('success', 'Votre profil à été mis à jour');
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('user/index.html.twig', [
            'userForm' => $form,
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
