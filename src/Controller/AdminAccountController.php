<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils$utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        // retourne le dernier nom d'utilisateur utilisé

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null, // hasError contiendra true s'il y a une erreur
            'username' => $username
        ]);
    }

    /**
     *Permet de se déconnecter
     *
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout() {
    }
}
