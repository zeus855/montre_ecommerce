<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'app_profil_')]
class ProfilController extends AbstractController
{
    #[Route('/show', name: 'show')]
    public function show(): Response
    {
        return $this->render('profil/show.html.twig');
    }
}
