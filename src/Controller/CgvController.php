<?php

namespace App\Controller;

use App\Repository\CGVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CgvController extends AbstractController
{
    #[Route('/cgv', name: 'app_cgv')]
    public function index(CGVRepository $cGVRepository): Response
    {
        return $this->render('cgv/index.html.twig', [            
            'cgv' => $cGVRepository->findOneBy([], ['id' => 'DESC'], 1),
        ]);
    }
}
