<?php

namespace App\Controller;

use App\Entity\LoginHistory;
use App\Service\LoginHistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PageController extends AbstractController
{
    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function index(Request $request, LoginHistoryService $lhs): Response
    {

        if (!$this->getUser()) {
            return $this->render('page/lp.html.twig');
        } else {
            // Lancement du service S'il vient de la connexion
            $requestArray = [
                "fromLogin" => $this->getParameter('APP_URL') . $this->generateUrl('app_login'),
                "referer" => $request->headers->get('referer'),
                "ip" => $request->getClientIp(),
                "userAgent" => $request->headers->get('user-agent'),
            ];

            // Lancement du LoginHistoryService s'il vient de la connexion
            if ($requestArray['fromLogin'] === $requestArray['referer']) {
                $lhs->addHistory($this->getUser(), $requestArray['userAgent'], $requestArray['ip']);
            }

            if (!$this->getUser()->isComplete()) {
                return $this->render('user/complete.html.twig');
            }
            return $this->render('page/homepage.html.twig');
        }
    }
}
