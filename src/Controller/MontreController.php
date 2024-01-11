<?php

namespace App\Controller;

use App\Entity\Montre;
use App\Form\MontreType;
use App\Repository\MontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/montre')]
class MontreController extends AbstractController
{
    #[Route('/', name: 'app_montre_index', methods: ['GET'])]

    public function index(MontreRepository $montreRepository): Response
    {
        return $this->render('montre/index.html.twig', [
            'montres' => $montreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_montre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $montre = new Montre();
        $form = $this->createForm(MontreType::class, $montre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($montre);
            $entityManager->flush();

            $this->addFlash('success', 'La montre a bien été ajoutée');

            return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('montre/new.html.twig', [
            'montre' => $montre,
            'form' => $form,
           
        ]);
        
    }

    #[Route('/{id}', name: 'app_montre_show', methods: ['GET'])]
    public function show(Montre $montre): Response
    {
        return $this->render('montre/show.html.twig', [
            'montre' => $montre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_montre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Montre $montre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MontreType::class, $montre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La montre a bien été modifiée');

            return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('montre/edit.html.twig', [
            'montre' => $montre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_montre_delete', methods: ['POST'])]
    public function delete(Request $request, Montre $montre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $montre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($montre);
            $entityManager->flush();
        }
        $this->addFlash('success', 'La montre a bien été supprimée');

        return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
    }
}
