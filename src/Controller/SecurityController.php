<?php

namespace App\Controller;

use App\Form\Builder\SecurityFormBuilder;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('error', $translator->trans($error->getMessageKey(), $error->getMessageData(), 'security'));
        }

        return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new Exception('Don\'t forget to activate logout in security.yaml');
    }


}