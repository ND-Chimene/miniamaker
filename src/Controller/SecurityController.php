<?php

namespace App\Controller;

use App\Entity\LoginHistory;
use Symfony\Component\Mime\Email;
use DeviceDetector\DeviceDetector;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request, EntityManagerInterface $em): Response
    {
        $deviceDetector = new DeviceDetector($request->headers->get('User-Agent'));
        $deviceDetector->parse();

        if ($this->getUser()) {
            $loginHistory = new LoginHistory();
            $loginHistory
                ->setUser($this->getUser())
                ->setIpAddress($request->getClientIp())
                ->setDevice($deviceDetector->getDevice())
                ->setOs($deviceDetector->getOs()['name'])
                ->setBrowser($deviceDetector->getClient()['name'])
            ;
            $em->persist($loginHistory);
            $em->flush();
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


    #[Route('/send/email', name: 'send_email')]
    public function testEmail(MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $emailTest = (new TemplatedEmail())
            ->from('contact@miniamaker.fr')
            ->to($this->getUser()->getEmail())
            ->subject('Nouvelle connexion détectée')
            ->htmlTemplate('send_email/index.html.twig');

        $mailer->send($emailTest);
        return $this->redirectToRoute('app_homepage');
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
